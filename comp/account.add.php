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

$account_groups = [
    "customer" => [
        "name" => "name",
        "type" => "category",
        "prefix" => "prefix",
        "father_name" => "fatherName",
        "cnic" => "cnic",
        "address" => "address",
        "city" => "city",
        "province" => "province",
        "country" => "country",
        "kin_name" => "nextKin",
        "kin_relation" => "relationship",
        "kin_cnic" => "nextKinCnic",
        "phone_no" => "phoneNo",
        "email" => "email",
        "email-format" => "email-format",
        "whatsapp_no" => "whtsNo",
        "guranter_name" => "guranterName",
        "guranter_cnic" => "guranterCnic",
        "image" => "img",
        "balance" => "openingBalance"
    ],
    "seller" => [
        "name" => "name",
        "type" => "category",
        "prefix" => "prefix",
        "father_name" => "fatherName",
        "cnic" => "cnic",
        "address" => "address",
        "city" => "city",
        "province" => "province",
        "country" => "country",
        "phone_no" => "phoneNo",
        "email" => "email",
        "email-format" => "email-format",
        "whatsapp_no" => "whtsNo",
        "image" => "img",
        "balance" => "openingBalance"
    ],
    "investor" => [
        "name" => "name",
        "type" => "category",
        "prefix" => "prefix",
        "father_name" => "fatherName",
        "cnic" => "cnic",
        "address" => "address",
        "city" => "city",
        "province" => "province",
        "country" => "country",
        "phone_no" => "phoneNo",
        "email" => "email",
        "email-format" => "email-format",
        "whatsapp_no" => "whtsNo",
        "image" => "img",
        "balance" => "openingBalance"
    ],
    "staff" => [
        "name" => "name",
        "type" => "category",
        "prefix" => "prefix",
        "father_name" => "fatherName",
        "cnic" => "cnic",
        "address" => "address",
        "city" => "city",
        "province" => "province",
        "country" => "country",
        "phone_no" => "phoneNo",
        "email" => "email",
        "email-format" => "email-format",
        "whatsapp_no" => "whtsNo",
        "image" => "img",
        "balance" => "openingBalance"
    ],
    "bank" => [
        "name" => "name",
        "type" => "category",
        "details" => "otherDetails",
        "number" => "accNumber",
        "branch" => "accountBranch",
        "action" => "paymentAction",
        "balance" => "expenseBalance"
    ],
    "expense" => [
        "name" => "name",
        "type" => "category",
        "details" => "otherDetails",
        "sub_group" => "subGroup",
        "action" => "paymentAction",
        "balance" => "expenseBalance"
    ]
];

foreach ($account_groups as $group_key => $group_fields) {
    if ($_POST['category'] == $group_key) {
        foreach ($group_fields as $key => $field) {
            $data[$key] = sanitize($conn, $_POST[$field]);
        }
    }
}

$data['id'] = selectedTableId($conn, "accounts", "acc_id", "AI", 3);
$data['project'] = $_SESSION['project'];

$formatter = [
    "(",
    ")",
    " ",
    "-",
    ","
];

if ($data['type'] == 'staff' || $data['type'] == 'seller' || $data['type'] == 'investor' || $data['type'] == 'customer') {
    $data['phone_no'] = str_replace($formatter, "", $data['phone_no']);
    $data['cnic'] = str_replace($formatter, "", $data['cnic']);
    $data['kin_cnic'] = str_replace($formatter, "", $data['kin_cnic']);
    $data['guranter_cnic'] = str_replace($formatter, "", $data['guranter_cnic']);
    $data['whtsNo'] = str_replace($formatter, "", $data['whatsapp_no']);
    $data['balance'] = str_replace(",", "", $data['balance']);
    $data['email'] = $data['email'] . $data['email-format'];
} elseif ($data['type'] == 'bank') {
    $data['number'] = str_replace($formatter, "", $data['number']);
    $data['balance'] = str_replace($formatter, "", $data['balance']);
} elseif ($data['type'] == 'expense') {
    $data['balance'] = str_replace($formatter, "", $data['balance']);
}

$query = "INSERT INTO `accounts`(`acc_id`, `type`, `project_id`, `created_date`, `created_by`) 
VALUES (?,?,?,?,?);";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssss", $data['id'], $data['type'], $data['project'], $created_date, $created_by);
$stmt->execute();

// Activity Record
$db_activity['date'] = date("d-m-Y", strtotime($created_date));
$db_activity['user_id'] = $_SESSION['id'];
$db_activity['activity'] = strtoupper($data['type']) . " Account &quot;" . $data['name'] . "&quot; with &quot;" . $data['id'] . "&quot; has been Created.";
$db_activity['project'] = $_SESSION['project'];
$db_activity['created_date'] = $created_date;
$db_activity['created_by'] = $created_by;
activity($conn, $db_activity);
// Activity Record

if ($data['type'] == "customer") {

    if (!empty($data['image']) && $data['image'] != 'profile.png') {
        $uploadDirectory = "../uploads/acc-profiles/";
        $image['parts'] = explode(";base64,", $data['image']);
        $image['type_aux'] = explode("image/", $image['parts'][0]);
        $image['base64'] = base64_decode($image['parts'][1]);
        $upload['img'] = uniqid($data['id'] . "-", true) . ".jpg";
        $upload['path'] = $uploadDirectory . $upload['img'];
        if (!file_put_contents($upload['path'], $image['base64'])) {
            $image_err = "false";
        }
    } else {
        $upload['img'] = "profile.png";
    }

    if (!empty($data['balance']) && $data['balance'] != '0') {
        $ledger = [
            'v-id' => ledgerVoucherId($conn),
            'type' => 'AccCreated',
            'source' => $data['id'],
            'remarks' => 'Recieveable Amount from Account &quot;' . $data['name'] . '&quot;',
            'credit' => "",
            'debit' => $data['balance'],
            'project' => $_SESSION['project'],
            'created_date' => $created_date,
            'created_by' => $created_by
        ];
        ledger($conn, $ledger);
    }

    $query = "INSERT INTO `customer`(`acc_id`, `name`, `prefix`, `father_name`, `cnic`, `address`, `city`, `province`, `country`, `phone_no`, `email`, `whts_no`, `kin_name`, `kin_relation`, `kin_cnic`, `guranter_name`, `guranter_cnic`, `img`, `project_id`, `created_date`, `created_by`) 
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssssssssssssssss", $data['id'], $data['name'], $data['prefix'], $data['father_name'], $data['cnic'], $data['address'], $data['city'], $data['province'], $data['country'], $data['phone_no'], $data['email'], $data['whatsapp_no'], $data['kin_name'], $data['kin_relation'], $data['kin_cnic'], $data['guranter_name'], $data['guranter_cnic'], $upload['img'], $data['project'], $created_date, $created_by);
    if ($stmt->execute()) {
        $location = "Location: ../Accounts?m=add_true";
        if (isset($image_err)) {
            $location .= "&image=" . $image_err;
        }
        header($location);
        exit();
    } else {
        header("Location: ../Accounts?m=add_false");
        exit();
    }

} elseif ($data['type'] == "bank") {

    if (!empty($data['balance']) && $data['balance'] != '0') {
        $ledger = [
            'v-id' => ledgerVoucherId($conn),
            'type' => 'AccCreated',
            'source' => $data['id'],
            'remarks' => ($data['action'] == 'credit') ? 'Payable amount to ' : 'Recieveable amount from' . ' Account &quot;' . $data['name'] . '&quot;',
            'credit' => ($data['action'] == 'credit') ? $data['balance'] : '',
            'debit' => ($data['action'] == 'debit') ? $data['balance'] : '',
            'project' => $_SESSION['project'],
            'created_date' => $created_date,
            'created_by' => $created_by,
        ];
        ledger($conn, $ledger);
    }

    $query = "INSERT INTO `bank`(`acc_id`, `name`, `details`, `number`, `branch`, `project_id`, `created_date`, `created_by`) 
    VALUES (?,?,?,?,?,?,?,?);";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssss", $data['id'], $data['name'], $data['details'], $data['number'], $data['branch'], $data['project'], $created_date, $created_by);
    if ($stmt->execute()) {
        header("Location: ../Accounts?m=add_true");
        exit();
    } else {
        header("Location: ../Accounts?m=add_false");
        exit();
    }

} elseif ($data['type'] == "expense") {

    if (!empty($data['balance']) && $data['balance'] != '0') {
        $ledger = [
            'v-id' => ledgerVoucherId($conn),
            'type' => 'AccCreated',
            'source' => $data['id'],
            'remarks' => 'Paid Amount to Account &quot;' . $data['name'] . '&quot;',
            'credit' => ($data['action'] == 'credit') ? $data['balance'] : '',
            'debit' => ($data['action'] == 'debit') ? $data['balance'] : '',
            'project' => $_SESSION['project'],
            'created_date' => $created_date,
            'created_by' => $created_by,
        ];
        ledger($conn, $ledger);
    }

    $query = "INSERT INTO `expense`(`acc_id`, `name`, `details`, `sub_group`, `project_id`, `created_date`, `created_by`) 
    VALUES (?,?,?,?,?,?,?);";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", $data['id'], $data['name'], $data['details'], $data['sub_group'], $data['project'], $created_date, $created_by);
    if ($stmt->execute()) {
        header("Location: ../Accounts?m=add_true");
        exit();
    } else {
        header("Location: ../Accounts?m=add_false");
        exit();
    }

} elseif ($data['type'] == "staff" || $data['type'] == "investor" || $data['type'] == "seller") {

    if (!empty($data['image']) && $data['image'] != 'profile.png') {
        $uploadDirectory = "../uploads/acc-profiles/";
        $image['parts'] = explode(";base64,", $data['image']);
        $image['type_aux'] = explode("image/", $image['parts'][0]);
        $image['base64'] = base64_decode($image['parts'][1]);
        // $upload['img'] = "AI-10-64f790ccaf3264.29505863.jpg";
        $upload['img'] = uniqid($data['id'] . "-", true) . ".jpg";
        $upload['path'] = $uploadDirectory . $upload['img'];
        if (!file_put_contents($upload['path'], $image['base64'])) {
            $image_err = "false";
        }
    } else {
        $upload['img'] = "profile.png";
    }

    if (!empty($data['balance']) && $data['balance'] != '0') {
        $ledger = [
            'v-id' => ledgerVoucherId($conn),
            'type' => 'AccCreated',
            'source' => $data['id'],
            'remarks' => 'Recieveable Amount from Account &quot;' . $data['name'] . '&quot;',
            'credit' => "",
            'debit' => $data['balance'],
            'project' => $_SESSION['project'],
            'created_date' => $created_date,
            'created_by' => $created_by
        ];
        ledger($conn, $ledger);
    }

    // echo "<pre>";
    // print_r($data);
    // print_r($upload);
    // print_r($db_activity);
    // print_r($ledger);
    // exit();

    $query = "INSERT INTO `" . $data['type'] . "`(`acc_id`, `name`, `prefix`, `father_name`, `cnic`, `address`, `city`, `province`, `country`, `phone_no`, `email`, `whts_no`, `img`, `project_id`, `created_date`, `created_by`) 
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssssssssss", $data['id'], $data['name'], $data['prefix'], $data['father_name'], $data['cnic'], $data['address'], $data['city'], $data['province'], $data['country'], $data['phone_no'], $data['email'], $data['whtsNo'], $upload['img'], $data['project'], $created_date, $created_by);
    if ($stmt->execute()) {
        $location = "Location: ../Accounts?m=add_true";
        if (isset($image_err)) {
            $location .= "&image=" . $image_err;
        }
        header($location);
        exit();
    } else {
        header("Location: ../Accounts?m=add_false");
        exit();
    }

}