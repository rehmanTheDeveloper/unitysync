<?php 
if ($_SESSION['loggedin'] != TRUE) {
    header('Location: index.php');
    exit;
}
?>