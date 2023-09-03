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
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-account") != true) {
    header("Location: ../Accounts?m=account_add_not_allow");
    exit();
}
################################ Role Validation ################################

$documents = $_FILES['docs'];

// echo "<pre>";
$id = encryptor("decrypt", $_POST['acc_id']);
// echo $id;
// exit();

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
                $upload_file['name'] = uniqid($_POST['file_names'][$i] . "-UNI-", true) . "." . $doc['ex_str_lower'];
                $upload_file['path'] = $upload_directory . $upload_file['name'];
                if (rename($upload_file['from'], $upload_file['path'])) {
                    $query = "INSERT INTO `document`(`acc_id`, `name`, `project_id`, `created_date`, `created_by`) VALUES (?,?,?,?,?);";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("sssss", $id, $upload_file['name'], $_SESSION['project'], $created_date, $created_by);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    $image_err = "image_false";
                }
                // print_r($upload_file);
            }
        }
    }

    
    header("Location: ../docs.view.php?m=add_true&i=".$_POST['acc_id']);
    exit();
}