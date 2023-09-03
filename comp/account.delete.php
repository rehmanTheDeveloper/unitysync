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
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "delete-user") != true) {
    header("Location: ../user.all.php?m=not_allowed");
    exit();
}
################################ Role Validation ################################

if (empty($_GET['i'])) {
    header("Location: ../user.all.php?m=false");
    exit();
}

echo "<pre>";
print_r(encryptor("decrypt", $_GET['i']));
echo "<br />";
exit();

$query = "SELECT `type` FROM `accounts` WHERE `acc_id` = '" . encryptor("decrypt", $_GET['i']) . "' AND `project_id` = '" . $_SESSION['project'] . "';";
$type = mysqli_fetch_assoc(mysqli_query($conn, $query));

if ($type['type'] == 'seller' || $type['type'] == 'investor') {
    $query = "SELECT `name` FROM `" . $type['type'] . "` WHERE `acc_id` = '" . encryptor("decrypt", $_GET['i']) . "' AND `project_id` = '" . $_SESSION['project'] . "';";
    $account = mysqli_fetch_assoc(mysqli_query($conn, $query));

    $query = "DELETE FROM `accounts` WHERE `acc_id` = '" . encryptor("decrypt", $_GET['i']) . "' AND `project_id` = '" . $_SESSION['project'] . "';";
    mysqli_query($conn, $query);

    // Activity Record
    $db_activity['date'] = date("d-m-Y", strtotime($created_date));
    $db_activity['user_id'] = $_SESSION['id'];
    $db_activity['activity'] = strtoupper($type['type']) . " Account &quot;" . $account['name'] . "&quot; with ID &quot;" . encryptor("decrypt", $_GET['i']) . "&quot; has been Deleted.";
    $db_activity['project'] = $_SESSION['project'];
    $db_activity['created_date'] = $created_date;
    $db_activity['created_by'] = $created_by;
    activity($conn, $db_activity);
    // Activity Record

    // TODO: Create a query for deleting all ledger history of selected account
    $query = "";
    // TODO: Create a query for deleting all ledger history of selected account

    $query = "DELETE FROM `" . $type['type'] . "` WHERE `id` = ? AND `project_id` = '" . $_SESSION['project'] . "';";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $_GET['i']);
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