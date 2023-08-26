<?php
// License Format Valdation
function isValidLicenseKey($string)
{
    // Define the regular expression pattern for the license key format
    $pattern = '/^[A-Z0-9]{5}-[A-Z0-9]{5}-[A-Z0-9]{5}-[A-Z0-9]{5}$/';

    // Use preg_match to check if the string matches the pattern
    // Returns 1 if the pattern matches, 0 if it does not match, or false on error
    return preg_match($pattern, $string) === 1;
}
// License Format Valdation

// Function to Check License Key That It exist
function validateLicenseKey($licenseKey, $productKey)
{
    // Replace the database credentials with your actual database information
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = "licensingSystem";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    if (empty($licenseKey)) {
        return "null";
    }
    if (!isValidLicenseKey($licenseKey)) {
        return "invalid";
    }
    $licenseKey = $conn->real_escape_string($licenseKey);
    $query = "SELECT * FROM licenses WHERE id = '$licenseKey' AND product = '$productKey';";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $licenseData = $result->fetch_assoc();

        if ($licenseData['registered_date'] != '00-00-0000' && $licenseData['status'] != 1) {
            return "active";
        }

        if ($licenseData['status'] == 0) {
            $conn->close();
            return 'inactive';
        } elseif ($licenseData['status'] == 1) {
            return 'active';
        } else {
            return 'expired';
        }
    } else {
        // License key not found
        $conn->close();
        return "invalid";
    }
}
// Function to Check License Key That It exist

function insertClientData($conn, $data)
{
    // Prepare the query using placeholders
    $query = "INSERT INTO `clients`(`product`, `license`, `name`, `email`, `country`, `phone_no`, `status`, `username`, `password`, `created_date`) 
    VALUES (?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($query);
    // Bind the parameters to the prepared statement
    $stmt->bind_param('ssssssssss', $data['productKey'], $data['license_key'], $data['name'], $data['email'], $data['country'], $data['phone_no'], $data['status'], $data['username'], $data['password'], $data['created_date']);
    // Execute the prepared statement
    $stmt->execute();
    $stmt->close();
}

function updateLicenseStatus($conn, $license_detail)
{
    // Prepare the query using placeholders
    $query = "UPDATE `licenses` SET `status`='1',`registered_date`=? WHERE `product` = ? AND `id` = ?;";
    $stmt = $conn->prepare($query);
    // Bind the parameters to the prepared statement
    $stmt->bind_param('sss', $license_detail['registered_date'], $license_detail['productKey'], $license_detail['license_key']);
    // Execute the prepared statement
    $stmt->execute();
    $stmt->close();
}

function insertProjectData($conn, $data)
{
    // Prepare the query using placeholders
    $query = "INSERT INTO `project`(`pro_id`, `name`, `category`, `address`, `city`, `country`, `phone_no`, `whatsapp_no`, `helpline_no`, `website`, `created_date`, `created_by`) 
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?);";
    $stmt = $conn->prepare($query);
    // Bind the parameters to the prepared statement
    $stmt->bind_param('ssssssssssss', $data['id'], $data['name'], $data['category'], $data['address'], $data['city'], $data['country'], $data['phone_no'], $data['whatsapp_no'], $data['helpline_no'], $data['website'], $data['created_date'], $data['created_by']);
    // Execute the prepared statement
    $stmt->execute();
    $stmt->close();
}

function insertUserData($conn, $data)
{
    // Prepare the query using placeholders
    $query = "INSERT INTO `users`(`u_id`, `license`, `f_name`, `email`, `country`, `phone_no`, `status`, `username`, `password`, `role`, `project_id`, `created_date`, `created_by`) 
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($query);
    // Bind the parameters to the prepared statement
    $stmt->bind_param('sssssssssssss', $data['id'], $data['license_key'], $data['name'], $data['email'], $data['country'], $data['phone_no'], $data['status'], $data['username'], $data['password'], $data['role'], $data['project'], $data['created_date'], $data['role']);
    // Execute the prepared statement
    $stmt->execute();
    $stmt->close();

    // $query = "SELECT * FROM `users` ORDER BY `id` DESC LIMIT 1;";
    // $selected_user = mysqli_fetch_assoc(mysqli_query($conn, $query));

    // $permissions = array
    // (
    //     "view-user-management",
    //     "add-user",
    //     "view-user",
    //     "edit-user",
    //     "delete-user",
    //     "add-user-role",
    //     "view-user-role",
    //     "edit-user-role",
    //     "delete-user-role",
    //     "view-project",
    //     "edit-project",
    //     "add-account",
    //     "view-account",
    //     "edit-account",
    //     "delete-account",
    //     "add-property",
    //     "edit-property",
    //     "view-property",
    //     "delete-property",
    //     "dashboard",
    //     "print",
    //     "view-ledger",
    //     "view-activity",
    //     "add-sale-property",
    //     "view-sale-property",
    //     "edit-sale-property",
    //     "delete-sale-property",
    //     "add-transfer-property",
    //     "view-transfer-property",
    //     "add-return-property",
    //     "view-return-property",
    //     "add-payment-pay",
    //     "view-payment-pay",
    //     "delete-payment-pay",
    //     "add-payment-receive",
    //     "view-payment-receive",
    //     "delete-payment-receive",
    //     "add-payment-transfer",
    //     "view-payment-transfer",
    //     "delete-payment-transfer"
    // );

    // $query = "INSERT INTO `roles`(`name`, `project_id`, `created_date`, `created_by`) VALUES (?,?,?,?);";
    // $stmt = $conn->prepare($query);
    // $stmt->bind_param("ssss", $data['role'], $data['project'], $data['created_date'], $selected_user['u_id']);
    // $stmt->execute();
    // $stmt->close();

    // $query = "SELECT * FROM `roles` ORDER BY `id` DESC LIMIT 1;";
    // $selected_role = mysqli_fetch_assoc(mysqli_query($conn, $query));

    // foreach ($permissions as $value) {
    //     if (empty($selected_permission)) {
    //         $query = "INSERT INTO `role_permissions`(`role`, `permission`, `project_id`, `created_date`, `created_by`) VALUES (?,?,?,?,?);";
    //         $stmt = $conn->prepare($query);
    //         $stmt->bind_param("sssss", $selected_role['id'], $value, $data['project'], $data['created_date'], $selected_user['u_id']);
    //         $stmt->execute();
    //         $stmt->close();
    //     }
    // }
}

function verficationLicenseKey($license)
{
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = "licensingSystem";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    $license_file_details = $license;
    if ($stmt = $conn->prepare('SELECT `id` FROM `licenses` WHERE `product` = ? AND `id` = ? AND `validity` = ? AND `status` = ? AND `registered_date` = ?;')) {
        $stmt->bind_param('sssss', $license_file_details['product'], $license_file_details['key'], $license_file_details['validity'], $license_file_details['status'], $license_file_details['registered_date']);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 1) {
            return "1"; // 1 => Matched
        } else {
            return "0"; // 0 => Not Matched
        }
    }
}
function updateLicenseFile($license, $license_path)
{
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = "licensingSystem";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    $license_file_details = $license;
    $license_data = array();
    if ($stmt = $conn->prepare('SELECT `product`, `id`, `validity`, `status`, `registered_date` FROM `licenses` WHERE `product` = ? AND `id` = ?;')) {
        $stmt->bind_param('ss', $license_file_details['product'], $license_file_details['key']);
        $stmt->execute();
        // Store the result
        $stmt->store_result();
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($license_data['product'], $license_data['key'], $license_data['validity'], $license_data['status'], $license_data['registered_date']);
            $stmt->fetch();
            $stmt->close();
            // Convert the registration date string to a timestamp
            $registrationTimestamp = strtotime($license_data['registered_date']);

            // Get the number of validity months from the $validity variable
            $validityMonths = $license_data['validity'];

            // Calculate the expiration date by adding the validity months to the registration date
            $expirationTimestamp = strtotime("+$validityMonths months", $registrationTimestamp);

            // Convert the expiration timestamp back to a date string
            $license_data['expiration_date'] = date('Y-m-d h:ia', $expirationTimestamp);
            $jsonString = json_encode($license_data, JSON_PRETTY_PRINT);
            file_put_contents($license_path, $jsonString);
        }
    }
}