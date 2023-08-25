<?php

date_default_timezone_set("Asia/Karachi");
$created_date = date("Y-m-d") . " " . date("h:i:sa");
$created_by = (isset($_SESSION['id'])) ? $_SESSION['id'] : "";
$current_month = date('F, Y');
define("LICENSE_PATH", "licenses/".$created_by."/license.json");

error_reporting(E_ALL);
ini_set('display_errors', 'On');

function conn($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME)
{
    $conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    if (!$conn) {
        die("Database Isn't connected");
    }
    return $conn;
}

function isLicenseExpired($registrationDate, $validityMonths)
{
    // Convert the registration date string to a DateTime object
    $registrationDateTime = new DateTime($registrationDate);

    // Calculate the expiration date by adding the validity months to the registration date
    $expirationDate = clone $registrationDateTime;
    $expirationDate->add(new DateInterval('P' . $validityMonths . 'M'));

    // Get the current date as a DateTime object
    $currentDateTime = new DateTime();

    // Compare the current date with the expiration date
    return $currentDateTime > $expirationDate;
}

function licenseExpirationDate($registrationDate, $validity)
{
    // Convert the registration date string to a timestamp
    $registrationTimestamp = strtotime($registrationDate);

    // Get the number of validity months from the $validity variable
    $validityMonths = $validity;

    // Calculate the expiration date by adding the validity months to the registration date
    $expirationTimestamp = strtotime("+$validityMonths months", $registrationTimestamp);

    // Convert the expiration timestamp back to a date string
    $expirationDate = date('Y-m-d h:ia', $expirationTimestamp);
    return $expirationDate;
}