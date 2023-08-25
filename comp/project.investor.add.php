<?php

session_start();
###################### Login & License Validation ######################
require("../temp/validate.login.temp.php"); #
$license_path = "../licenses/" . $_SESSION['license_username'] . "/license.json"; #
require("../auth/license.validate.functions.php"); #
require("../temp/validate.license.temp.php"); #
###################### Login & License Validation ######################

######################## Database Connection #######################
require("../auth/config.php"); #
require("../auth/functions.php"); #
$conn = conn("localhost", "root", "", "communiSync"); #
######################## Database Connection #######################

################################ Role Validation ################################
if ($_SESSION['role'] != 'super-admin') {
    header("Location: ../dashboard.php?message=masti");
    exit();
}
################################ Role Validation ################################

$doc = [];

// echo "<pre>";
// print_r($_POST);

foreach ($_POST as $key => $value) {
    if ($key != "file_names") {
        $data[$key] = sanitize($conn, $value);
    }
}

$documents = $_FILES['docs'];

// echo "<pre>";

// print_r($_FILES);
$query = "SELECT `id`,`name` FROM `investor` WHERE `acc_id` = '" . $data['investor'] . "' AND `project_id` = '" . $_SESSION['project'] . "';";
$result = mysqli_fetch_assoc(mysqli_query($conn, $query));
if ($result) {
    if (isset($documents) && !empty($documents)) {
        for ($i = 0; $i < count($documents['name']); $i++) {
            $cache_directory = "../uploads/cache/";
            $upload_directory = "../uploads/docs/";
            $doc['name'] = $documents['name'][$i];
            $doc["ex"] = pathinfo($doc['name'], PATHINFO_EXTENSION);
            $doc['ex_str_lower'] = strtolower($doc['ex']);

            if ($doc['ex'] == "pdf" || $doc['ex'] == "jpg" || $doc['ex'] == "jpeg" || $doc['ex'] == "png") {
                if (file_exists($cache_directory . $doc['name'] . "-" . $_SESSION['project'])) {
                    $upload_file["from"] = $cache_directory . $doc['name'] . "-" . $_SESSION['project'];
                    $upload_file['name'] = $_POST['file_names'][$i] . "." . $doc['ex_str_lower'];
                    $upload_file['path'] = $upload_directory . $upload_file['name'];
                    rename($upload_file['from'], $upload_file['path']);
                    if (rename($upload_file['from'], $upload_file['path'])) {
                        $query = "INSERT INTO `document`(`acc_id`, `name`, `project_id`, `created_date`, `created_by`) VALUES (?,?,?,?,?);";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("sssss", $data['investor'], $upload_file['name'], $_SESSION['project'], $created_date, $created_by);
                        $stmt->execute();
                        $stmt->close();
                    } else {
                        $image_err = "image_false";
                    }
                    // print_r($upload_file);
                }
            }
        }
    }

    // Activity Record
    $db_activity['date'] = date("d-m-Y", strtotime($created_date));
    $db_activity['user_id'] = $_SESSION['id'];
    $db_activity['activity'] = "Investor &quot;" . $result['name'] . "&quot; has been Added in Project.";
    $db_activity['project'] = $_SESSION['project'];
    $db_activity['created_date'] = $created_date;
    $db_activity['created_by'] = $created_by;
    activity($conn, $db_activity);
    // Activity Record

    // print_r($db_activity);

    $query = "INSERT INTO `area_investor`(`kanal`, `marla`, `feet`, `ratio`, `acc_id`, `project_id`, `created_date`, `created_by`) VALUES (?,?,?,?,?,?,?,?);";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssss", $data['kanal'], $data['marla'], $data['feet'], $data['ratio'], $data['investor'], $_SESSION['project'], $created_date, $created_by);
    if ($stmt->execute()) {
        header("Location: ../project.view.php?message=add_true");
        exit();
    } else {
        header("Location: ../project.view.php?message=add_false");
        exit();
    }
} else {
    header("Location: ../project.view.php?message=not_found");
    exit();
}