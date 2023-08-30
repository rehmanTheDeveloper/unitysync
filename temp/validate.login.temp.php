<?php 
if ($_SESSION['loggedin'] != TRUE) {
    header('Location: Login');
    exit;
}
?>