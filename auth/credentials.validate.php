<?php
require_once 'DB.handler.php';

// Replace with your database credentials
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'approve_system';

$databaseHandler_license = new DatabaseHandler($servername, $username, $password, $dbname);

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = "communiSync";

$databaseHandler_product = new DatabaseHandler($servername, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];

    // Check if the username or password already exists in the database
    if ($databaseHandler_license->isUsernameExists('clients', $username) && $databaseHandler_product->isUsernameExists('users', $username)) {
        // print_r($databaseHandler_license->isUsernameExists('clients', $username));
        echo 'invalid';
    } else {
        echo 'valid';
    }

}

$databaseHandler_license->closeConnection();
$databaseHandler_product->closeConnection();
