<?php

session_start();
#################### Login & License Validation ####################
require("../temp/validate.login.temp.php"); #
$license_path = "../licenses/" . $_SESSION['license_username'] . "/license.json"; #
require("../auth/license.validate.functions.php"); #
require("../temp/validate.license.temp.php"); #
#################### Login & License Validation ####################

######################## Database Connection #######################
require("../auth/config.php"); #
require("../auth/functions.php"); #
$conn = conn("localhost", "root", "", "communiSync"); #
######################## Database Connection #######################

$file = $_POST['file'];

$uploadDirectory = "../uploads/profiles/";
$image['parts'] = explode(";base64,", $file);
$image['type_aux'] = explode("image/", $image['parts'][0]);
$image['base64'] = base64_decode($image['parts'][1]);

$img = [];
if (isset($file) && !empty($file)) {

    $uploadImg = uniqid($_SESSION['id'] . "-", true) . ".jpg";
    $uploadPath = $uploadDirectory . $uploadImg;

    // ! Deleting Method of image in directory
    $deleteImage = $conn->query("SELECT `img` FROM `users` WHERE `u_id` = '" . $_SESSION['id'] . "';");
    $deleteImage = $deleteImage->fetch_assoc();
    // ! Deleting Method of image in directory

    if ($deleteImage['img'] != "profile.png") {
        unlink("../uploads/profiles/".$deleteImage['img']);
    }

    // print_r($deleteImage);
    // exit();

    if (file_put_contents($uploadPath, $image['base64'])) {

        $_SESSION['img'] = $uploadImg;
        // Feed Activity in DB
        $db_activity['date'] = date("d-m-Y", strtotime($created_date));
        $db_activity['user_id'] = $_SESSION['id'];
        $db_activity['activity'] = $_SESSION['name'] . " modified His Profile.";
        $db_activity['project'] = $_SESSION['project'];
        $db_activity['created_date'] = $created_date;
        $db_activity['created_by'] = $created_by;
        activity($conn, $db_activity);
        // Feed Activity in DB

        $query = "UPDATE `users` SET `img` = ? WHERE `u_id` = ?;";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $uploadImg, $_SESSION['id']);
        if ($stmt->execute()) {
            echo "valid";
        } else {
            echo "invalid";
        }

    } else {
        echo "invalid";
    }
} else {
    echo "invalid";
}