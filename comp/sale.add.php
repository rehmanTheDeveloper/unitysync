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
$ledger_obj = new Ledger($PDO_conn);

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-account") != true) {
    header("Location: ../account.all.php?m=account_add_not_allow");
    exit();
}
################################ Role Validation ################################

echo "<pre>";
// print_r($_POST);

$sale_groups = [
    "installment" => [
        "date" => "date",
        "customer" => "customer",
        /*"property_type" => "propertyType",*/
        "plot" => "plot",
        /*"flat" => "flat",*/
        "type" => "paymentType",
        "remarks" => "instRemarks",
        "price" => "instPropertyPrice",
        "advance_payment" => "advancePayment",
        "account" => "instBankAccount",
        "inst_date" => "instDate",
        "installments" => "totalInstallments",
    ],
    "net_cash" => [
        "date" => "date",
        "customer" => "customer",
        /*"property_type" => "propertyType",*/
        "plot" => "plot",
        /*"flat" => "flat",*/
        "type" => "paymentType",
        "remarks" => "netCashRemarks",
        "price" => "netCashPropertyPrice",
        "amount" => "netAmount",
        "account" => "netCashBankAccount"
    ],
];

foreach ($sale_groups as $group_key => $single_sale) {
    if ($_POST['paymentType'] == $group_key) {
        foreach ($single_sale as $sale_key => $sale) {
            $data[$sale_key] = sanitize($conn, $_POST[$sale]);
        }
    }
}
$data['id'] = selectedTableId($conn, "sales", "sale_id", "SI", 3);
$data['price'] = str_replace(",", "", $data['price']);

// if ($data['property_type'] == "plot") {
    $data['property'] = $data['plot'];
//     unset($data['plot'], $data['flat']);
// } elseif ($data['property_type'] == "flat") {
//     $data['property'] = $data['flat'];
//     unset($data['plot'], $data['flat']);
// }

$query = "SELECT `name` FROM `customer` WHERE `acc_id` = '" . $data['customer'] . "' AND `project_id` = '" . $_SESSION['project'] . "';";
$customer = mysqli_fetch_assoc(mysqli_query($conn, $query));
if ($data['account'] != "cash") {
    $query = "SELECT `name` FROM `bank` WHERE `acc_id` = '" . $data['account'] . "' AND `project_id` = '" . $_SESSION['project'] . "';";
    $bank = mysqli_fetch_assoc(mysqli_query($conn, $query));
}

$query = "INSERT INTO `sales`(`sale_id`, `type`, `project_id`, `created_date`, `created_by`) 
VALUES (?,?,?,?,?);";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssss", $data['id'], $data['type'], $_SESSION['project'], $created_date, $created_by);
$stmt->execute();

// echo "<pre>";
// $ledger = [
    // 'v-id' => ledgerVoucherId($conn),
    // 'type' => 'sale',
    // 'sale_id' => $data['id'],
    // 'source' => "colony",
    // 'pay_to' => $data['customer'],
    // 'remarks' => /*(($data['property_type'] == "plot") ?*/ "Plot " /*: "Flat ") */ . '"' . $data['property'] . '" has been sold to "' . $customer['name'] . '"',
    // 'amount' => $data['price'],
    // 'project' => $_SESSION['project'],
    // 'created_date' => $created_date,
    // 'created_by' => $created_by
// ];
// print_r($ledger);
// $ledger_validation = $ledger_obj->insert($ledger);

if ($data['type'] == "installment") {
    $data['price'] = str_replace(",", "", $data['price']);
    $data['advance_payment'] = str_replace(",", "", $data['advance_payment']);

    if ($data['account'] != 'cash') {
        $ledger = [
            'v-id' => ledgerVoucherId($conn),
            'type' => 'sale',
            'sale_id' => $data['id'],
            'source' => $data['customer'],
            'pay_to' => $data['account'],
            'remarks' => $data['remarks'],
            'amount' => $data['advance_payment'],
            'project' => $_SESSION['project'],
            'created_date' => $created_date,
            'created_by' => $created_by
        ];
        // print_r($ledger);
        $ledger_validation = $ledger_obj->insert($ledger);
    } else {
        $ledger = [
            'v-id' => ledgerVoucherId($conn),
            'type' => 'sale',
            'sale_id' => $data['id'],
            'source' => $data['customer'],
            'pay_to' => "colony",
            'remarks' => $data['remarks'],
            'amount' => $data['advance_payment'],
            'project' => $_SESSION['project'],
            'created_date' => $created_date,
            'created_by' => $created_by
        ];
        // print_r($ledger);
        $ledger_validation = $ledger_obj->insert($ledger);
    }

    // Activity Record
    $db_activity['date'] = date("d-m-Y", strtotime($created_date));
    $db_activity['user_id'] = $_SESSION['id'];
    $db_activity['activity'] = /*(($data['property_type'] == "plot") ?*/ "Plot " /*: "Flat ") */. "&quot;" . $data['property'] . "&quot; Purchased By &quot;" . $customer['name'] . "&quot; on " . $data['installments'] . " Installments.";
    $db_activity['project'] = $_SESSION['project'];
    $db_activity['created_date'] = $created_date;
    $db_activity['created_by'] = $created_by;
    activity($conn, $db_activity);
    // Activity Record
    // print_r($db_activity);
    // print_r($data);

    $query = "INSERT INTO `sale_installment`(`date`, `pty_id`, `acc_id`, `installments`, `price`, `advance_payment`, `inst_date`, `sale_id`, `project_id`, `created_date`, `created_by`) 
    VALUES (?,?,?,?,?,?,?,?,?,?,?);";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssssss", $data['date'], $data['property'], $data['customer'], $data['installments'], $data['price'], $data['advance_payment'], $data['inst_date'], $data['id'], $_SESSION['project'], $created_date, $created_by);
    if ($stmt->execute()) {
        header("Location: ../sale.all.php?m=add_true");
        exit();
    } else {
        header("Location: ../sale.all.php?m=add_false");
        exit();
    }
    // print_r($stmt);
    // exit();
} elseif ($data['type'] == "net_cash") {
    $data['price'] = str_replace(",", "", $data['price']);
    $data['amount'] = str_replace(",", "", $data['amount']);

    if ($data['account'] != 'cash') {
        $ledger = [
            'v-id' => ledgerVoucherId($conn),
            'type' => 'sale',
            'sale_id' => $data['id'],
            'source' => $data['customer'],
            'pay_to' => $data['account'],
            'remarks' => 'Net Amount of Sale "' . $data['id'] . '" From Account "' . $customer['name'] . '"',
            'amount' => $data['amount'],
            'project' => $_SESSION['project'],
            'created_date' => $created_date,
            'created_by' => $created_by
        ];
        $ledger_validation = $ledger_obj->insert($ledger);
    } else {
        $ledger = [
            'v-id' => ledgerVoucherId($conn),
            'type' => 'sale',
            'sale_id' => $data['id'],
            'source' => $data['customer'],
            'pay_to' => "colony",
            'remarks' => 'Net Amount of Sale "' . $data['id'] . '" From Account "' . $customer['name'] . '"',
            'amount' => $data['amount'],
            'project' => $_SESSION['project'],
            'created_date' => $created_date,
            'created_by' => $created_by
        ];
        $ledger_validation = $ledger_obj->insert($ledger);
    }

    // Activity Record
    $db_activity['date'] = date("d-m-Y", strtotime($created_date));
    $db_activity['user_id'] = $_SESSION['id'];
    $db_activity['activity'] = (($data['property_type'] == "plot") ? "Plot " : "Flat ") . "&quot;" . $data['property'] . "&quot; Purchased By &quot;" . $customer['name'] . "&quot; on Net Cash.";
    $db_activity['project'] = $_SESSION['project'];
    $db_activity['created_date'] = $created_date;
    $db_activity['created_by'] = $created_by;
    activity($conn, $db_activity);
    // Activity Record
    // print_r($db_activity);

    $query = "INSERT INTO `sale_net_cash`(`date`, `pty_id`, `acc_id`, `price`, `net_amount`, `sale_id`, `project_id`, `created_date`, `created_by`) 
    VALUES (?,?,?,?,?,?,?,?,?);";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssss", $data['date'], $data['property'], $data['customer'], $data['price'], $data['amount'], $data['id'], $_SESSION['project'], $created_date, $created_by);
    if ($stmt->execute()) {
        header("Location: ../sale.all.php?m=add_true");
        exit();
    } else {
        header("Location: ../sale.all.php?m=add_false");
        exit();
    }
    // print_r($stmt);
    // print_r($data);
}