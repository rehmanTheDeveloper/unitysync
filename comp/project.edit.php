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
if ($_SESSION['role'] !== "super-admin") {
    header("Location: ../dashboard.php?message=not_allow");
    exit();
}
################################ Role Validation ################################

$phone_format = array(
    "(",
    ")",
    " ",
    "-"
);

foreach ($_POST as $key => $value) {
    $data[$key] = sanitize($conn, $value);
}

$data['commercial_sqft'] = ($data['commercial_sqft'][0] == "0")?substr($data['commercial_sqft'],1):$data['commercial_sqft'];
$data['residential_sqft'] = ($data['residential_sqft'][0] == "0")?substr($data['residential_sqft'],1):$data['residential_sqft'];
$data['phoneNo'] = str_replace($phone_format,"",$data['phoneNo']);
$data['whtsNo'] = str_replace($phone_format,"",$data['whtsNo']);
$data['helplineNo'] = str_replace($phone_format,"",$data['helplineNo']);

$db_activity['date'] = date("d-m-Y", strtotime($created_date));
$db_activity['user_id'] = $_SESSION['id'];
$db_activity['activity'] = "Project Details Updated.";
$db_activity['project'] = $_SESSION['project'];
$db_activity['created_date'] = $created_date;
$db_activity['created_by'] = $created_by;
activity($conn, $db_activity);

$query = "UPDATE `project` SET `name`= ?,`address`= ?,`city`= ?,`country`= ?,`phone_no`= ?,`whatsapp_no`= ?,`helpline_no`= ?,`website`= ?,`commercial_sqft`=?, `residential_sqft`=?, `sqft_per_marla`=?,`fb_link`= ?,`yt_link`= ?,`inst_link`= ?,`tw_link`= ? WHERE `pro_id` = ?;";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssssssssssssssss", $data['name'], $data['address'], $data['city'], $data['country'], $data['phoneNo'], $data['whtsNo'], $data['helplineNo'], $data['website'], $data['commercial_sqft'], $data['residential_sqft'], $data['sqft_per_marla'], $data['fbLink'], $data['ytLink'], $data['itLink'], $data['twLink'], $_SESSION['project']);

if ($stmt->execute()) {
    header("Location: ../project.view.php?message=edit_true");
    exit();
} else {
    header("Location: ../project.view.php?message=edit_false");
    exit();
}
