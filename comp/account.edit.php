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
    header("Location: ../Dashboard?m=not_allow");
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

echo "<pre>";

// Account Exist or not .. ??
$query = "SELECT `id` FROM `accounts` WHERE `acc_id` = '".encryptor("decrypt",$_POST['id'])."' AND `type` = '".$_POST['accGroup']."' AND `project_id` = '".$_SESSION['project']."';";
$result = mysqli_fetch_assoc(mysqli_query($conn, $query));

if (empty($result)) {
    header("Location: ../account.edit.php?i=".$_POST['id']."&m=edit_false");
    exit();
} else {
    $result = "";
}

$data['phoneNo'] = str_replace($phone_format,"",$data['phoneNo']);
$data['whsNo'] = str_replace($phone_format,"",$data['whsNo']);
$data['email'] = $data['email'].$data['email-format'];
$data['cnicNum'] = str_replace($phone_format,"",$data['cnicNum']);
$data['id'] = encryptor("decrypt", $data['id']);

// print_r($_POST);
// print_r($data);

if ($data['accGroup'] == 'seller' || $data['accGroup'] == 'investor') {
    $query = "SELECT `img` FROM `".$data['accGroup']."` WHERE `acc_id` = '".encryptor("decrypt",$_POST['id'])."' AND `project_id` = '".$_SESSION['project']."';";
    $result = mysqli_fetch_assoc(mysqli_query($conn, $query));
}

if (!empty($data['img'])) {
    $uploadDirectory = "../uploads/acc-profiles/";
    if ($data['img'] != $result['img']) {
        $image['parts'] = explode(";base64,", $data['img']);
        $image['type_aux'] = explode("image/", $image['parts'][0]);
        $image['base64'] = base64_decode($image['parts'][1]);
        $upload['img'] = $result['img'];
        $upload['path'] = $uploadDirectory . $upload['img'];
        if (!file_put_contents($upload['path'], $image['base64'])) {
            $image_err = "false";
        }
        // print_r($image);
    } else {
        $upload['img'] = $result['img'];
    }
    // print_r($upload);
}

// echo "<pre>";
// print_r($result);
// exit();

if ($data['accGroup'] == 'seller' || $data['accGroup'] == 'investor') {
    // Activity Record
    $db_activity['date'] = date("d-m-Y", strtotime($created_date));
    $db_activity['user_id'] = $_SESSION['id'];
    $db_activity['activity'] = strtoupper($data['accGroup'])." Account &quot;" . $data['accTitle'] . "&quot; has been Updated.";
    $db_activity['project'] = $_SESSION['project'];
    $db_activity['created_date'] = $created_date;
    $db_activity['created_by'] = $created_by;
    activity($conn, $db_activity);
    // Activity Record

    // print_r($db_activity);

    $query = "UPDATE `".$data['accGroup']."` SET `name`=?,`prefix`=?,`father_name`=?,`cnic`=?,`address`=?,`city`=?,`province`=?,`country`=?,`phone_no`=?,`email`=?,`whts_no`=?,`img`=? WHERE `acc_id` =? AND `project_id` =?;";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssssssss", $data['accTitle'], $data['title'], $data['fName'], $data['cnicNum'], $data['address'], $data['city'], $data['province'], $data['country'], $data['phoneNo'], $data['email'], $data['whsNo'], $upload['img'], $data['id'], $_SESSION['project']);

    // print_r($stmt);
    // exit();

    if ($stmt->execute()) {
        header("Location: ../account.view.php?i=".$_POST['id']."&m=edit_true");
        exit();
    } else {
        header("Location: ../account.edit.php?i=".$_POST['id']."&m=edit_false");
        exit();
    }
}