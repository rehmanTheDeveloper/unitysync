<?php 

session_start();
#################### Login & License Validation ####################
require("../temp/validate.login.temp.php"); #
$license_path = "../licenses/" . $_SESSION['license_username'] . "/license.json"; #
require("../auth/license.validate.functions.php"); #
require("../temp/validate.license.temp.php"); #
#################### Login & License Validation ####################

if(!empty($_FILES['file'])){ 
    //  output files folder
    $targetDir = "../uploads/cache/"; 
    
    // set any name or remain the same name
    $fileName = basename($_FILES['file']['name'])."-".$_SESSION['project'];
    $targetFilePath = $targetDir.$fileName;
    
    if(move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)){ 
       echo 'valid';
    } else {
        echo "invalid";
    }
   
} 
?>