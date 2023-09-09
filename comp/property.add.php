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
    header("Location: ../account.all.php?m=account_add_not_allow");
    exit();
}
################################ Role Validation ################################

// $property_groups = [
//     "plot" => [
//         "type" => "type",
//         "number" => "plotNo",
//         "block_method" => "plotBlockMethod",
//         "plot_block" => "plotBlock",
//         "block" => "plotBlockName",
//         "street" => "plotStreet",
//         "category" => "plotCategory",
//         "length" => "plotLength",
//         "width" => "plotWidth",
//         "sqft" => "plotMarla",
//         "remarks" => "remarks"
//     ],
//     "flat" => [
//         "type" => "type",
//         "number" => "flatNo",
//         "block_method" => "flatBlockMethod",
//         "flat_block" => "flatBlock",
//         "block" => "flatBlockName",
//         "street" => "flatStreet",
//         "category" => "flatCategory",
//         "length" => "flatLength",
//         "width" => "flatWidth",
//         "sqft" => "flatMarla",
//         "remarks" => "remarks"
//     ]
// ];

// foreach ($property_groups as $group_key => $group_fields) {
//     if ($_POST['type'] == $group_key) {
//         foreach ($group_fields as $key => $field) {
//             $data[$key] = sanitize($conn, $_POST[$field]);
//         }
//     }
// }

foreach ($_POST as $key => $value) {
    $data[$key] = sanitize($conn, $value);
}

$query = "SELECT `residential_sqft`,`commercial_sqft`,`sqft_per_marla` FROM `project` WHERE `pro_id` = '".$_SESSION['project']."';";
$project_details = mysqli_fetch_assoc(mysqli_query($conn, $query));

$data['id'] = selectedTableId($conn, "properties", "pty_id", "PR", 3);
$data['sqft'] = $data['marla'] * $project_details['sqft_per_marla'];
$data['rate'] = str_replace(",","",$data['rate']);

if ($data['category'] == "residential") {
    if ($data['sqft'] > $project_details['residential_sqft']) {
        header("Location: ../property.config.php?m=add_false");
        exit();
    }
} elseif ($data['category'] == "commercial") {
    if ($data['sqft'] > $project_details['commercial_sqft']) {
        header("Location: ../property.config.php?m=add_false");
        exit();
    }
}

$query = "INSERT INTO `properties`(`type`, `pty_id`, `project_id`, `created_date`, `created_by`) 
VALUES (?,?,?,?,?);";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssss", $data['type'], $data['id'], $_SESSION['project'], $created_date, $created_by);
$stmt->execute();

// Activity Record
$db_activity['date'] = date("d-m-Y", strtotime($created_date));
$db_activity['user_id'] = $_SESSION['id'];
$db_activity['activity'] = strtoupper($data['type']) . " #" . $data['number'] . " with ID &quot;" . $data['id'] . "&quot; has been Created.";
$db_activity['project'] = $_SESSION['project'];
$db_activity['created_date'] = $created_date;
$db_activity['created_by'] = $created_by;
activity($conn, $db_activity);
// Activity Record

if ($data['blockMethod'] == 'typeBlock') {
    $query = "SELECT `id` FROM `blocks` WHERE `name` = '".$data['block']."' AND `street` = '".$data['street']."' AND `project_id` = '".$_SESSION['project']."';";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 0) {
        $query = "INSERT INTO `blocks`(`name`, `street`, `project_id`, `created_date`, `created_by`) VALUES (?,?,?,?,?);";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $data['blockName'], $data['street'], $_SESSION['project'], $created_date, $created_by);
        $stmt->execute();

        $query = "SELECT `id` FROM `blocks` ORDER BY `id` DESC LIMIT 1;";
        $block_id = mysqli_fetch_assoc(mysqli_query($conn, $query));
        $data['block'] = $block_id['id'];
    }
}

// echo "<pre>";
// print_r($data);
// print_r($db_activity);
// exit();

$query = "INSERT INTO `".$data['type']."`(`pty_id`, `number`, `block`, `category`, `length`, `width`, `sqft`, `rate`, `remarks`, `project_id`, `created_date`, `created_by`) 
VALUES (?,?,?,?,?,?,?,?,?,?,?,?);";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssssssssssss", $data['id'], $data['number'], $data['block'], $data['category'], $data['length'], $data['width'], $data['sqft'], $data['rate'], $data['remarks'], $_SESSION['project'], $created_date, $created_by);
if ($stmt->execute()) {
    header("Location: ../property.all.php?m=add_true");
    exit();
} else {
    header("Location: ../property.config.php?m=add_false");
    exit();
}