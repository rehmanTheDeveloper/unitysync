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
$DB_Connection = new DB("localhost", "unitySync", "root", ""); #
$PDO_conn = $DB_Connection->getConnection(); #
######################## Database Connection #######################

require "../object/ledger.php";

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-account") != true) {
    header("Location: ../account.all.php?m=account_add_not_allow");
    exit();
}
################################ Role Validation ################################

foreach ($_POST as $key => $value) {
    $data[$key] = sanitize($conn, $value);
}

$data['amount'] = str_replace(",","",$data['amount']);
$voucher = ledgerVoucherId($conn);
$type = "paid";

if ($data['sourceAccount'] != "cash") {
    $query = "SELECT `name` FROM `bank` WHERE `acc_id` = '".$data['sourceAccount']."' AND `project_id` = '".$_SESSION['project']."';";
    $source_account = mysqli_fetch_assoc(mysqli_query($conn, $query));
    $data['sourceName'] = $source_account['name'];
} else {
    $data['sourceName'] = "Colony";
}

$ledger = [
    'v-id' => $voucher,
    'type' => 'payment',
    'source' => ($data['sourceAccount'] == "cash")?"colony":$data['sourceAccount'],
    'pay_to' => $data['payTo'],
    'remarks' => $data['remarks'],
    'amount' => $data['amount'],
    'project' => $_SESSION['project'],
    'created_date' => $created_date,
    'created_by' => $created_by
];
$ledger_obj = new Ledger($PDO_conn);
$ledger_validation = $ledger_obj->insert($ledger);

// Activity Record
$db_activity['date'] = date("d-m-Y", strtotime($created_date));
$db_activity['user_id'] = $_SESSION['id'];
$db_activity['activity'] = "Payment Paid From \"".$data['sourceName']."\" to ";
$db_activity['project'] = $_SESSION['project'];
$db_activity['created_date'] = $created_date;
$db_activity['created_by'] = $created_by;
activity($conn, $db_activity);
// Activity Record

// echo "<pre>";
// print_r($data);
// print_r($ledger);
// print_r($source_account);
// print_r($db_activity);
// exit();

$query = "INSERT INTO `payment`(`type`, `date`, `voucher_id`, `project_id`, `created_date`, `created_by`) 
VALUES (?,?,?,?,?,?);";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssssss", $type, $data['date'], $voucher, $_SESSION['project'], $created_date, $created_by);
if ($stmt->execute()) {
    header("Location: ../payment.paid.php?m=add_true");
    exit();
} else {
    header("Location: ../payment.paid.php?m=add_false");
    exit();
}