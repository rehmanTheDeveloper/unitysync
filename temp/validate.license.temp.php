<?php
$license_json = json_decode(file_get_contents($license_path), true);
if (verficationLicenseKey($license_json) == "0") {
    updateLicenseFile($license_json, $license_path);
}
$license_json = json_decode(file_get_contents($license_path), true);
if (!empty($license_json)) {
    if (validateLicenseKey($license_json['key'], $license_json['product']) == "expired" || $license_json['status'] == "2") {
        // header("Location: license.expired.php");
        exit();
    } elseif (validateLicenseKey($license_json['key'], $license_json['product']) == "inactive" || $license_json['status'] == "0") {
        header("Location: license.deactivated.php");
        exit();
    }
} else {
    echo "License Key Couldn't Found ...";
    exit();
}
?>