<?php
session_start();
include('config.php');
include('functions.php');
// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if (!isset($_POST['username'], $_POST['password'])) {
    // Could not get the data that should have been sent.
    header('Location: ../index.php?m=true');
}

$conn = conn("localhost", "root", "", "unitySync");
if ($stmt = $conn->prepare('SELECT `u_id`, `password`,`f_name`,`s_name`,`role`,`img`,`project_id` FROM `users` WHERE `username` = ? AND `role` = "super-admin";')) {
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $password, $f_name, $s_name, $role, $img, $project);
        $stmt->fetch();
        // Account exists, now we verify the password.
        if ($_POST['password'] != $password) {
            // Incorrect password
            header('Location: ../index.php?m=p_wrong_pass');
            exit();
        }
    } else {
        $stmt = $conn->prepare('SELECT `u_id`, `password`,`f_name`,`s_name`,`role`,`img`,`status`,`project_id`, `created_by` FROM `users` WHERE `username` = ?;');
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $password, $f_name, $s_name, $role, $img, $status, $project, $created_by);
            $stmt->fetch();
            if ($status == "1") {
                if ($_POST['password'] != $password) {
                    // Incorrect password
                    header('Location: ../index.php?m=p_wrong_pass');
                    exit();
                }
            } else {
                header("Location: ../index.php?m=p_user_inactive");
                exit();
            }
        } else {
            // Incorrect username
            header('Location: ../index.php?m=p_not_exist');
            exit();
        }
        $stmt->close();

        if (is_numeric($role)) {
            $query = "SELECT `name` FROM `roles` WHERE `id` = '$role';";
            $selected_role = mysqli_fetch_assoc(mysqli_query($conn, $query));
            $role = $selected_role['name'];
        }

        $query = 'SELECT * FROM `users` WHERE `role` = "super-admin" AND `project_id` = "'.$project.'";';
        $admin_user_of_project = mysqli_fetch_assoc(mysqli_query($conn, $query));
    }
}
$conn->close();

$conn = conn("localhost", "root", "", "licensingSystem");
if ($stmt = $conn->prepare('SELECT `license` FROM `clients` WHERE `username` = ? AND `product` = ?;')) {
    // Bind parameters
    if ($role == 'super-admin') {
        $stmt->bind_param('ss', $_POST['username'], $_POST['productKey']);
    } else {
        $stmt->bind_param('ss', $admin_user_of_project['username'], $_POST['productKey']);
    }
    $stmt->execute();
    // Store the result
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($license['key']);
        $stmt->fetch();
        $stmt->close();
        if ($stmt = $conn->prepare('SELECT `validity`, `status`, `registered_date` FROM `licenses` WHERE `product` = ? AND `id` = ?;')) {
            $stmt->bind_param('ss', $_POST['productKey'], $license['key']);
            $stmt->execute();
            // Store the result
            $stmt->store_result();
            if ($stmt->num_rows === 1) {
                $stmt->bind_result($license['validity'], $license['status'], $license['registered_date']);
                $stmt->fetch();
                $license['productKey'] = $_POST['productKey'];
                $stmt->close();
                if (isLicenseExpired($license['registered_date'], $license['validity']) && $license['status'] > 0) {
                    header("Location: ../license.expired.php");
                    exit();
                }
            }
        }
    } else {
        // Incorrect username
        header('Location: ../index.php?m=l_not_reg');
        exit();
    }
}
$conn->close();

if (is_int($license['status'])) {
    // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
    $_SESSION['loggedin'] = TRUE;
    if ($role == "super-admin") {
        $_SESSION['license_username'] = $_POST['username'];
    } else {
        $_SESSION['license_username'] = $admin_user_of_project['username'];
    }
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['name'] = "$f_name $s_name";
    $_SESSION['id'] = $id;
    $_SESSION['role'] = $role;
    $_SESSION['project'] = $project;
    $_SESSION['img'] = $img;

    if ($role == 'super-admin') {
        $license_json['key'] = $license['key'];
        $license_json['product'] = $license['productKey'];
        $license_json['status'] = $license['status'];
        $license_json['validity'] = $license['validity'];
        $license_json['registered_date'] = date("Y-m-d h:ia", strtotime($license['registered_date']));
        $license_json['expiration_date'] = licenseExpirationDate($license['registered_date'], $license['validity']);

        $jsonString = json_encode($license_json, JSON_PRETTY_PRINT);
        $directoryPath = '../licenses/' . $_POST['username'];
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }
        $filePath = $directoryPath . '/license.json';
        file_put_contents($filePath, $jsonString);
    }

    header('Location: ../dashboard.php?m=login_true');
    exit();

}

?>