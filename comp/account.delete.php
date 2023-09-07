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
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "delete-user") != true) {
    header("Location: ../user.all.php?m=not_allowed");
    exit();
}
################################ Role Validation ################################

if (empty($_GET['i'])) {
    header("Location: ../user.all.php?m=false");
    exit();
}

$dir_docs = "../uploads/docs/";
$profile_directory = "../uploads/acc-profiles/";
$refined = encryptor("decrypt", $_GET['i']);

$query = "SELECT `type` FROM `accounts` WHERE `acc_id` = '" . $refined . "' AND `project_id` = '" . $_SESSION['project'] . "';";
$type = mysqli_fetch_assoc(mysqli_query($conn, $query));

if ($type['type'] == 'bank' || $type['type'] == 'expense') {
    $query = "SELECT `name` FROM `" . $type['type'] . "` WHERE `acc_id` = '" . $refined . "' AND `project_id` = '" . $_SESSION['project'] . "';";
    $account = mysqli_fetch_assoc(mysqli_query($conn, $query));

    $query = "SELECT * FROM `document` WHERE `acc_id` = '" . $refined . "' AND `project_id` = '" . $_SESSION['project'] . "';";
    $documents = fetch_Data($conn, $query);
    if (!empty($documents)) {
        foreach ($documents as $key => $document) {
            if (file_exists($dir_docs . $document['name'])) {
                if (unlink($dir_docs . $document['name'])) {
                    $query = "DELETE FROM `document` WHERE `name` = ? AND `project_id` = '" . $_SESSION['project'] . "';";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("s", $document['name']);
                    $stmt->execute();
                    // print_r($document);
                }
            }
        }
    }

    $query = "DELETE FROM `accounts` WHERE `acc_id` = '" . $refined . "' AND `project_id` = '" . $_SESSION['project'] . "';";
    mysqli_query($conn, $query);

    // Activity Record
    $db_activity['date'] = date("d-m-Y", strtotime($created_date));
    $db_activity['user_id'] = $_SESSION['id'];
    $db_activity['activity'] = strtoupper($type['type']) . " Account &quot;" . $account['name'] . "&quot; with ID &quot;" . $refined . "&quot; has been Deleted.";
    $db_activity['project'] = $_SESSION['project'];
    $db_activity['created_date'] = $created_date;
    $db_activity['created_by'] = $created_by;
    activity($conn, $db_activity);
    // Activity Record

    $query = "DELETE FROM `ledger` WHERE `source` = ? AND `project_id` = ?;";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $refined, $_SESSION['project']);
    $stmt->execute();

    $query = "DELETE FROM `" . $type['type'] . "` WHERE `acc_id` = ? AND `project_id` = '" . $_SESSION['project'] . "';";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $refined);
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: ../account.all.php?m=deleted_true");
        exit();
    } else {
        $stmt->close();
        header("Location: ../account.all.php?m=deleted_false");
        exit();
    }

} elseif ($type['type'] == 'seller' || $type['type'] == 'investor' || $type['type'] == 'staff' || $type['type'] == 'customer') {
    $query = "SELECT `name`,`img` FROM `" . $type['type'] . "` WHERE `acc_id` = '" . $refined . "' AND `project_id` = '" . $_SESSION['project'] . "';";
    $account = mysqli_fetch_assoc(mysqli_query($conn, $query));

    $query = "SELECT * FROM `document` WHERE `acc_id` = '" . $refined . "' AND `project_id` = '" . $_SESSION['project'] . "';";
    $documents = fetch_Data($conn, $query);

    if (!empty($documents)) {
        foreach ($documents as $key => $document) {
            if (file_exists($dir_docs . $document['name'])) {
                if (unlink($dir_docs . $document['name'])) {
                    $query = "DELETE FROM `document` WHERE `name` = ? AND `project_id` = '" . $_SESSION['project'] . "';";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("s", $document['name']);
                    $stmt->execute();
                    // print_r($document);
                }
            }
        }
    }

    if ($account['img'] != 'profile.png') {
        unlink($profile_directory . $account['img']);
    }

    $query = "DELETE FROM `accounts` WHERE `acc_id` = '" . $refined . "' AND `project_id` = '" . $_SESSION['project'] . "';";
    mysqli_query($conn, $query);

    // Activity Record
    $db_activity['date'] = date("d-m-Y", strtotime($created_date));
    $db_activity['user_id'] = $_SESSION['id'];
    $db_activity['activity'] = strtoupper($type['type']) . " Account &quot;" . $account['name'] . "&quot; with ID &quot;" . $refined . "&quot; has been Deleted.";
    $db_activity['project'] = $_SESSION['project'];
    $db_activity['created_date'] = $created_date;
    $db_activity['created_by'] = $created_by;
    activity($conn, $db_activity);
    // Activity Record

    $query = "DELETE FROM `ledger` WHERE `source` = ? AND `project_id` = ?;";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $refined, $_SESSION['project']);
    $stmt->execute();

    $query = "DELETE FROM `" . $type['type'] . "` WHERE `id` = ? AND `project_id` = '" . $_SESSION['project'] . "';";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $refined);
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: ../account.all.php?m=deleted_true");
        exit();
    } else {
        $stmt->close();
        header("Location: ../account.all.php?m=deleted_false");
        exit();
    }
} else {
    header("Location: ../account.all.php?m=false");
    exit();
}

?>