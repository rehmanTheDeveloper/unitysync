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
$conn = conn("localhost", "root", "", "unitySync"); #
######################## Database Connection #######################

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-account") != true) {
    header("Location: ../Accounts?m=account_add_not_allow");
    exit();
}
################################ Role Validation ################################
$data = [];

$surcharge_groups = [
    "subGroup" => [
        "type" => "type",
        "name" => "expenseSubGroup",
        "remarks" => "expenseSubGroupRemarks"
    ]
];

foreach ($surcharge_groups as $group_key => $group_fields) {
    if ($_POST['type'] == $group_key) {
        foreach ($group_fields as $key => $field) {
            $data[$key] = sanitize($conn, $_POST[$field]);
        }
    }
}

if ($data['type'] == 'subGroup') {
    echo "<pre>";
    print_r($data);

    $query = "SELECT * FROM `expense_sub_groups` WHERE `name` = '".$data['name']."' AND `project_id` = '".$_SESSION['project']."';";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        header("Location: ../surcharge.php?m=group_exist");
        exit();
    }

    $query = "INSERT INTO `expense_sub_groups`(`name`, `remarks`, `project_id`, `created_date`, `created_by`) 
    VALUES (?,?,?,?,?);";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $data['name'], $data['remarks'], $_SESSION['project'], $created_date, $created_by);
    if ($stmt->execute()) {
        header("Location: ../surcharge.php?m=group_add_true");
        exit();
    } else {
        header("Location: ../surcharge.php?m=group_add_false");
        exit();
    }
}