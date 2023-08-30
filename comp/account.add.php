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
    header("Location: ../account.all.php?message=account_add_not_allow");
    exit();
}
################################ Role Validation ################################

foreach ($_POST as $key => $value) {
    $data[$key] = sanitize($conn, $value);
}
$data['id'] = voucherId($conn, "accounts", "acc_id", "AI", 3);
$data['project'] = $_SESSION['project'];

$phone_format = array(
    "(",
    ")",
    " ",
    "-"
);

// echo "<pre>";
$data['phoneNo'] = str_replace($phone_format,"",$data['phoneNo']);
$data['cnic'] = str_replace("-","",$data['cnic']);
$data['nextKinCnic'] = str_replace("-","",$data['nextKinCnic']);
$data['whtsNo'] = str_replace($phone_format,"",$data['whtsNo']);
$data['openingBalance'] = str_replace(",","",$data['openingBalance']);
$data['email'] = $data['email'].$data['email-format'];
// print_r($data);
// exit();

$query = "INSERT INTO `accounts`(`acc_id`, `type`, `project_id`, `created_date`, `created_by`) 
VALUES (?,?,?,?,?);";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssss", $data['id'], $data['category'], $data['project'], $created_date, $created_by);
$stmt->execute();

if (!empty($data['img'])) {
    $uploadDirectory = "../uploads/acc-profiles/";
    $image['parts'] = explode(";base64,", $data['img']);
    $image['type_aux'] = explode("image/", $image['parts'][0]);
    $image['base64'] = base64_decode($image['parts'][1]);
    $upload['img'] = uniqid($data['id'] . "-", true) . ".jpg";
    $upload['path'] = $uploadDirectory . $upload['img'];
    if (!file_put_contents($upload['path'], $image['base64'])) {
        $image_err = "false";
    }
}

if ($data['category'] == "seller") {
    // Activity Record
    $db_activity['date'] = date("d-m-Y", strtotime($created_date));
    $db_activity['user_id'] = $_SESSION['id'];
    $db_activity['activity'] = "Seller Account &quot;" . $data['name'] . "&quot; has been Created.";
    $db_activity['project'] = $_SESSION['project'];
    $db_activity['created_date'] = $created_date;
    $db_activity['created_by'] = $created_by;
    activity($conn, $db_activity);
    // Activity Record

    $query = "INSERT INTO `seller`(`acc_id`, `name`, `prefix`, `father_name`, `cnic`, `address`, `city`, `province`, `country`, `phone_no`, `email`, `whts_no`, `balance`, `img`, `project_id`, `created_date`, `created_by`) 
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssssssssssss", $data['id'], $data['name'], $data['prefix'], $data['fatherName'], $data['cnic'], $data['address'], $data['city'], $data['province'], $data['country'], $data['phoneNo'], $data['email'], $data['whtsNo'], $data['openingBalance'], $upload['img'], $data['project'], $created_date, $created_by);
    if ($stmt->execute()) {
        $location = "Location: ../account.all.php?message=add_true";
        if (isset($image_err)) {
            $location .= "&image=".$image_err;
        }
        header($location);
        exit();
    } else {
        header("Location: ../account.all.php?message=add_false");
        exit();
    }
} elseif ($data['category'] == "investor") {
    // Activity Record
    $db_activity['date'] = date("d-m-Y", strtotime($created_date));
    $db_activity['user_id'] = $_SESSION['id'];
    $db_activity['activity'] = "Investor Account &quot;" . $data['name'] . "&quot; has been Created.";
    $db_activity['project'] = $_SESSION['project'];
    $db_activity['created_date'] = $created_date;
    $db_activity['created_by'] = $created_by;
    activity($conn, $db_activity);
    // Activity Record

    $query = "INSERT INTO `investor`(`acc_id`, `name`, `prefix`, `father_name`, `cnic`, `address`, `city`, `province`, `country`, `phone_no`, `email`, `whts_no`, `balance`, `img`, `project_id`, `created_date`, `created_by`) 
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssssssssssss", $data['id'], $data['name'], $data['prefix'], $data['fatherName'], $data['cnic'], $data['address'], $data['city'], $data['province'], $data['country'], $data['phoneNo'], $data['email'], $data['whtsNo'], $data['openingBalance'], $upload['img'], $data['project'], $created_date, $created_by);
    if ($stmt->execute()) {
        $location = "Location: ../account.all.php?message=add_true";
        if (isset($image_err)) {
            $location .= "&image=".$image_err;
        }
        header($location);
        exit();
    } else {
        header("Location: ../account.all.php?message=add_false");
        exit();
    }
}