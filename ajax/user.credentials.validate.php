<?php

session_start();
require("../auth/config.php");
$conn = conn('localhost', 'root', '', 'pine-valley');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];

    $username = $conn->real_escape_string($username);
    $query = "SELECT * FROM `users` WHERE username = '$username';";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        echo "invalid";
    } else {
        echo "valid";
    }
}

?>