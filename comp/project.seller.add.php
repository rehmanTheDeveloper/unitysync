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
if ($_SESSION['role'] != 'super-admin') {
    header("Location: ../index.php?m=masti");
    exit();
}
################################ Role Validation ################################

$doc = [];
$documents = $_FILES['docs'];

foreach ($_POST as $key => $value) {
    if ($key != "file_names") {
        $data[$key] = sanitize($conn, $value);
    }
}

$data['amount'] = str_replace(",","",$data['amount']);
$data['period'] = str_replace(",","",$data['period']);
$data['kanal'] = str_replace(",","",$data['kanal']);
$data['marla'] = str_replace(",","",$data['marla']);
$data['feet'] = str_replace(",","",$data['feet']);

// echo "<pre>";
// print_r($data);
// print_r($documents);
// exit();

$query = "SELECT `id`,`name` FROM `seller` WHERE `acc_id` = '" . $data['seller'] . "' AND `project_id` = '" . $_SESSION['project'] . "';";
$result = mysqli_fetch_assoc(mysqli_query($conn, $query));
if ($result) {
    if (isset($documents) && !empty($documents)) {
        for ($i = 0; $i < count($documents['name']); $i++) {
            $cache_directory = "../uploads/cache/";
            $upload_directory = "../uploads/docs/";
            $doc['name'] = $documents['name'][$i];
            $doc["ex"] = pathinfo($doc['name'], PATHINFO_EXTENSION);
            $doc['ex_str_lower'] = strtolower($doc['ex']);

            if ($doc['ex'] == "pdf" || $doc['ex'] == "jpg" || $doc['ex'] == "jpeg" || $doc['ex'] == "png") {
                if (file_exists($cache_directory . $doc['name'] . "-" . $_SESSION['project'])) {
                    $upload_file["from"] = $cache_directory . $doc['name'] . "-" . $_SESSION['project'];
                    $upload_file['name'] = uniqid($_POST['file_names'][$i]."-UNI-",true) . "." . $doc['ex_str_lower'];
                    $upload_file['path'] = $upload_directory . $upload_file['name'];
                    if (rename($upload_file['from'], $upload_file['path'])) {
                        $query = "INSERT INTO `document`(`acc_id`, `name`, `project_id`, `created_date`, `created_by`) VALUES (?,?,?,?,?);";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("sssss", $data['seller'], $upload_file['name'], $_SESSION['project'], $created_date, $created_by);
                        $stmt->execute();
                        $stmt->close();
                    } else {
                        $image_err = "image_false";
                    }
                    // print_r($upload_file);
                }
            }
        }
    }

    $ledger = [
        'v-id' => ledgerVoucherId($conn),
        'type' => 'ProjectSeller',
        'source' => $data['seller'],
        'remarks' => number_format((($data['kanal'] * 20) * 272.25) + ($data['marla'] * 272.25) + $data['feet']).' Sqft. are purchased from &quot'.$result['name'].'&quot',
        'credit' => $data['amount'],
        'debit' => '',
        'project' => $_SESSION['project'],
        'created_date' => $created_date,
        'created_by' => $created_by,
    ];
    ledger($conn, $ledger);

    // Activity Record
    $db_activity['date'] = date("d-m-Y", strtotime($created_date));
    $db_activity['user_id'] = $_SESSION['id'];
    $db_activity['activity'] = "Seller &quot;" . $result['name'] . "&quot; has been Added in Project.";
    $db_activity['project'] = $_SESSION['project'];
    $db_activity['created_date'] = $created_date;
    $db_activity['created_by'] = $created_by;
    activity($conn, $db_activity);
    // Activity Record

    // print_r($db_activity);

    $query = "INSERT INTO `area_seller`(`kanal`, `marla`, `feet`, `amount`, `period`, `acc_id`, `project_id`, `created_date`, `created_by`) VALUES (?,?,?,?,?,?,?,?,?);";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssss", $data['kanal'], $data['marla'], $data['feet'], $data['amount'], $data['period'], $data['seller'], $_SESSION['project'], $created_date, $created_by);
    // print_r($stmt);
    if ($stmt->execute()) {
        header("Location: ../project.view.php?m=seller_add_true");
        exit();
    } else {
        header("Location: ../project.view.php?m=seller_add_false");
        exit();
    }
} else {
    header("Location: ../project.view.php?m=not_found");
    exit();
}