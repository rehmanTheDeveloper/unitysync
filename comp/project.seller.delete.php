<?php

session_start();
###################### Login & License Validation ######################
require("../temp/validate.login.temp.php");                            #
$license_path = "../licenses/".$_SESSION['license_username']."/license.json";  #
require("../auth/license.validate.functions.php");                     #
require("../temp/validate.license.temp.php");                          #
###################### Login & License Validation ######################

######################## Database Connection #######################
require("../auth/config.php");                                     #
require("../auth/functions.php");                                  #
$conn = conn("localhost", "root", "", "communiSync");              #
######################## Database Connection #######################

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "delete-user") != true) {
    header("Location: ../user.all.php?message=not_allowed");
    exit();
}
################################ Role Validation ################################

if (empty($_GET['id'])) {
    header("Location: ../project.view.php?message=not_found");
    exit();
}

$query = "SELECT `name` FROM `seller` WHERE `acc_id` = '".$_GET['id']."' AND `project_id` = '".$_SESSION['project']."';";
$result = mysqli_fetch_assoc(mysqli_query($conn, $query));

// Activity Record
$db_activity['date'] = date("d-m-Y", strtotime($created_date));
$db_activity['user_id'] = $_SESSION['id'];
$db_activity['activity'] = "Seller &quot;" . $result['name'] . "&quot; has been Removed from Project.";
$db_activity['project'] = $_SESSION['project'];
$db_activity['created_date'] = $created_date;
$db_activity['created_by'] = $created_by;
activity($conn, $db_activity);
// Activity Record

// echo "<pre>";
// print_r($db_activity);
// exit();

$query = "UPDATE `project` SET `commercial_sqft` = '0', `residential_sqft` = '0', `sqft_per_marla` = '0' WHERE `pro_id` = '".$_SESSION['project']."';";
mysqli_query($conn, $query);

$query = "DELETE FROM `area_seller` WHERE `acc_id` = ? AND `project_id` = '".$_SESSION['project']."';";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_GET['id']);
if ($stmt->execute()) {
    $stmt->close();
    header("Location: ../project.view.php?message=seller_delete_true");
    exit();
} else {
    $stmt->close();
    header("Location: ../project.view.php?message=seller_delete_false");
    exit();
}