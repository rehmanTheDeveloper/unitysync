<?php

require("config.php");
require("functions.php");
require("license.validate.functions.php");

$project = [];
$data = [];
$conn = conn('localhost', 'root', '', 'approve_system');
foreach ($_POST as $key => $value) {
    if ($key == "project") {
        foreach ($value as $pro_key => $pro) {
            $project[$pro_key] = $pro;
        }
    } else {
        $data[$key] = sanitize($conn, $value);
    }
}

if (empty($project)) {
    header("Location: ../sign-up.php?message=missing_data");
    exit();
}

$data['created_date'] = $created_date;
$data['status'] = 1;
$data['role'] = "super-admin";
$data['id'] = voucherId(conn("localhost", "root", "", "communiSync"), "users", "u_id", "UI", 3);
$data['project'] = voucherId(conn("localhost", "root", "", "communiSync"), "project", "pro_id", "PJ", 3);
$license_detail['registered_date'] = date("Y-m-d h:ia", strtotime($created_date));
$license_detail['productKey'] = $data['productKey'];
$license_detail['license_key'] = $data['license_key'];
$project['created_date'] = $created_date;
$project['id'] = $data['project'];
$project['created_by'] = $data['role'];

// Activity In project has been Added through this variable
$db_activity['date'] = date("d-m-Y", strtotime($created_date));
$db_activity['user_id'] = $data['role'];
$db_activity['activity'] = "Colony has been registered by &quot;".$data['name']."&quot;.";
$db_activity['project'] = $data['project'];
$db_activity['created_date'] = $created_date;
$db_activity['created_by'] = $data['role'];

insertClientData($conn, $data);
updateLicenseStatus($conn, $license_detail);
$conn->close();

// echo "<pre>";
// print_r($data);
// print_r($license_detail);
// print_r($project);
// print_r($db_activity);
// exit();

$conn = conn('localhost', 'root', '', "communiSync");
insertUserData($conn, $data);
insertProjectData($conn, $project);
activity($conn, $db_activity);
$conn->close();

header("Location: ../index.php?message=registered");