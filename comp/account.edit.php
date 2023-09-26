<?php

session_start();
###################### Login & License Validation ######################
require("../temp/validate.login.temp.php"); #
$license_path = "../license/" . $_SESSION['license_username'] . "/license.json"; #
require("../auth/license.validate.functions.php"); #
require("../temp/validate.license.temp.php"); #
###################### Login & License Validation ######################

######################## Database Connection #######################
require("../auth/config.php"); #
require("../auth/functions.php"); #
$conn = conn("localhost", "root", "", "unitySync"); #
######################## Database Connection #######################

################################ Role Validation ################################
if ($_SESSION['role'] !== "super-admin") {
    header("Location: ../index.php?m=not_allow");
    exit();
}
################################ Role Validation ################################

$phone_format = array(
    "(",
    ")",
    " ",
    ",",
    "-"
);

$account_groups = [
    "customer" => [
        "name" => "accTitle",
        "type" => "accGroup",
        "prefix" => "title",
        "father_name" => "fName",
        "cnic" => "cnicNum",
        "address" => "address",
        "city" => "city",
        "province" => "province",
        "country" => "country",
        "kin_name" => "nextKin",
        "kin_relation" => "relationship",
        "kin_cnic" => "nextKinCNIC",
        "phone_no" => "phoneNo",
        "email" => "email",
        "email-format" => "email-format",
        "whatsapp_no" => "whtsNo",
        "guranter_name" => "guranterName",
        "guranter_cnic" => "guranterCnic",
        "image" => "img",
        "id" => "id"
    ],
    "seller" => [
        "name" => "accTitle",
        "type" => "accGroup",
        "prefix" => "title",
        "father_name" => "fName",
        "cnic" => "cnicNum",
        "address" => "address",
        "city" => "city",
        "province" => "province",
        "country" => "country",
        "phone_no" => "phoneNo",
        "email" => "email",
        "email-format" => "email-format",
        "whatsapp_no" => "whsNo",
        "image" => "img",
        "id" => "id"
    ],
    "investor" => [
        "name" => "accTitle",
        "type" => "accGroup",
        "prefix" => "title",
        "father_name" => "fName",
        "cnic" => "cnicNum",
        "address" => "address",
        "city" => "city",
        "province" => "province",
        "country" => "country",
        "phone_no" => "phoneNo",
        "email" => "email",
        "email-format" => "email-format",
        "whatsapp_no" => "whsNo",
        "image" => "img",
        "id" => "id"
    ],
    "staff" => [
        "name" => "accTitle",
        "type" => "accGroup",
        "prefix" => "title",
        "father_name" => "fName",
        "cnic" => "cnicNum",
        "address" => "address",
        "city" => "city",
        "province" => "province",
        "country" => "country",
        "phone_no" => "phoneNo",
        "email" => "email",
        "email-format" => "email-format",
        "whatsapp_no" => "whsNo",
        "image" => "img",
        "id" => "id"
    ],
    "bank" => [
        "name" => "accTitle",
        "type" => "accGroup",
        "details" => "otherDetails",
        "number" => "accNumber",
        "branch" => "accountBranch",
        "id" => "id"
    ],
    "expense" => [
        "name" => "accTitle",
        "type" => "accGroup",
        "details" => "otherDetails",
        "sub_group" => "subGroup",
        "branch" => "accountBranch",
        "id" => "id"
    ]
];

foreach ($account_groups as $group_key => $group_fields) {
    if ($_POST['accGroup'] == $group_key) {
        foreach ($group_fields as $key => $field) {
            $data[$key] = sanitize($conn, $_POST[$field]);
        }
    }
}

$query = "SELECT `id` FROM `accounts` WHERE `acc_id` = '" . encryptor("decrypt", $_POST['id']) . "' AND `type` = '" . $data['type'] . "' AND `project_id` = '" . $_SESSION['project'] . "';";
$result = mysqli_fetch_assoc(mysqli_query($conn, $query));

if (empty($result)) {
    header("Location: ../account.edit.php?i=" . $_POST['id'] . "&m=edit_false");
    exit();
} else {
    $result = "";
}

if ($data['type'] == 'seller' || $data['type'] == 'investor' || $data['type'] == 'staff' || $data['type'] == 'customer') {
    $data['phone_no'] = str_replace($phone_format, "", $data['phone_no']);
    $data['whatsapp_no'] = str_replace($phone_format, "", $data['whatsapp_no']);
    $data['email'] = $data['email'] . $data['email-format'];
    $data['cnic'] = str_replace($phone_format, "", $data['cnic']);
} elseif ($data['type'] == 'bank') {
    $data['number'] = str_replace($phone_format, "", $data['number']);
}
$data['id'] = encryptor("decrypt", $_POST['id']);

if ($data['type'] == 'seller' || $data['type'] == 'investor' || $data['type'] == 'staff' || $data['type'] == 'customer') {
    $query = "SELECT `img` FROM `" . $data['type'] . "` WHERE `acc_id` = '" . encryptor("decrypt", $_POST['id']) . "' AND `project_id` = '" . $_SESSION['project'] . "';";
    $result = mysqli_fetch_assoc(mysqli_query($conn, $query));
    $uploadDirectory = "../uploads/acc-profiles/";
    $image['parts'] = explode(";base64,", $data['image']);
    $image['type_aux'] = explode("image/", $image['parts'][0]);
    $image['base64'] = base64_decode($image['parts'][1]);
    if ($result['img'] == "profile.png") {
        $upload['image'] = uniqid($data['id'] . "-", true) . ".jpg";
    } else {
        $upload['image'] = $result['img'];
    }
    $upload['path'] = $uploadDirectory . $upload['image'];
    if (!file_put_contents($upload['path'], $image['base64'])) {
        $image_err = "false";
    }
}

// Activity Record
$db_activity['date'] = date("d-m-Y", strtotime($created_date));
$db_activity['user_id'] = $_SESSION['id'];
$db_activity['activity'] = strtoupper($data['type']) . " Account &quot;" . $data['name'] . "&quot; with ID &quot;" . $data['id'] . "&quot; has been Updated.";
$db_activity['project'] = $_SESSION['project'];
$db_activity['created_date'] = $created_date;
$db_activity['created_by'] = $created_by;
activity($conn, $db_activity);
// Activity Record

if ($data['type'] == 'customer') {

    $query = "UPDATE `" . $data['type'] . "` SET `name`=?,`prefix`=?,`father_name`=?,`cnic`=?,`address`=?,`city`=?,`province`=?,`country`=?,`phone_no`=?,`email`=?,`whts_no`=?,`kin_name`=?,`kin_relation`=?,`kin_cnic`=?,`guranter_name`=?,`guranter_cnic`=?,`img`=? WHERE `acc_id` =? AND `project_id` =?;";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssssssssssssss", $data['name'], $data['prefix'], $data['father_name'], $data['cnic'], $data['address'], $data['city'], $data['province'], $data['country'], $data['phone_no'], $data['email'], $data['whatsapp_no'], $data['kin_name'], $data['kin_relation'], $data['kin_cnic'], $data['guranter_name'], $data['guranter_cnic'], $upload['image'], $data['id'], $_SESSION['project']);
    if ($stmt->execute()) {
        header("Location: ../account.view.php?i=" . $_POST['id'] . "&m=edit_true");
        exit();
    } else {
        header("Location: ../account.edit.php?i=" . $_POST['id'] . "&m=edit_false");
        exit();
    }

} elseif ($data['type'] == 'bank') {

    $query = "UPDATE `" . $data['type'] . "` SET `name`=?,`details`=?,`number`=?,`branch`=? WHERE `acc_id` =? AND `project_id` =?;";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $data['name'], $data['details'], $data['number'], $data['branch'], $data['id'], $_SESSION['project']);
    if ($stmt->execute()) {
        header("Location: ../account.view.php?i=" . $_POST['id'] . "&m=edit_true");
        exit();
    } else {
        header("Location: ../account.edit.php?i=" . $_POST['id'] . "&m=edit_false");
        exit();
    }

} elseif ($data['type'] == 'expense') {

    $query = "UPDATE `expense` SET `name`=?,`details`=?,`sub_group`=? WHERE `acc_id` =? AND `project_id` =?;";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $data['name'], $data['details'], $data['sub_group'], $data['id'], $_SESSION['project']);
    if ($stmt->execute()) {
        header("Location: ../account.view.php?i=" . $_POST['id'] . "&m=edit_true");
        exit();
    } else {
        header("Location: ../account.edit.php?i=" . $_POST['id'] . "&m=edit_false");
        exit();
    }

} elseif ($data['type'] == 'seller' || $data['type'] == 'investor' || $data['type'] == 'staff') {

    $query = "UPDATE `" . $data['type'] . "` SET `name`=?,`prefix`=?,`father_name`=?,`cnic`=?,`address`=?,`city`=?,`province`=?,`country`=?,`phone_no`=?,`email`=?,`whts_no`=?,`img`=? WHERE `acc_id` =? AND `project_id` =?;";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssssssss", $data['name'], $data['prefix'], $data['father_name'], $data['cnic'], $data['address'], $data['city'], $data['province'], $data['country'], $data['phone_no'], $data['email'], $data['whatsapp_no'], $upload['image'], $data['id'], $_SESSION['project']);
    if ($stmt->execute()) {
        header("Location: ../account.view.php?i=" . $_POST['id'] . "&m=edit_true");
        exit();
    } else {
        header("Location: ../account.edit.php?i=" . $_POST['id'] . "&m=edit_false");
        exit();
    }

}