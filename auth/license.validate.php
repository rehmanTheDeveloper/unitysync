<?php
sleep(1);
session_start();
// If the user is logged in redirect to the Dashboard page...
if ($_SESSION['loggedin'] == TRUE) {
    echo json_encode(['status' => 'logged-in']);
}

require("license.validate.functions.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $licenseKey = $_POST['licenseKey'];
    $productKey = $_POST['productKey'];
    $pageName = $_POST['pageName'];
    if (validateLicenseKey($licenseKey, $productKey) == 'inactive') {
        // License is valid, read the content from super.user.create.txt
        $htmlContent = file_get_contents("super.user.create.txt");
        echo json_encode(['status' => 'valid', 'htmlContent' => $htmlContent]);

    } elseif (validateLicenseKey($licenseKey, $productKey) == 'active') {
        echo json_encode(['status' => 'registered']);

    } elseif (validateLicenseKey($licenseKey, $productKey) == 'expired') {
        echo json_encode(['status' => 'expire']);

    } elseif (validateLicenseKey($licenseKey, $productKey) == 'invalid') {
        echo json_encode(['status' => 'invalid']);

    } else {
        echo json_encode(['status' => 'null']);
    }
}