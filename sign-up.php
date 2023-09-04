<?php
// We need to use sessions
session_start();
// If the user is logged in redirect to the Dashboard page...
if ($_SESSION['loggedin'] == TRUE) {
    header('Location: Dashboard');
    exit;
}


$title = "Register Plato Account";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('temp/head.temp.php'); ?>
</head>

<body>
    <main>

        <!-- Section -->
        <section style="min-height: 100vh;" class="bg-soft d-flex align-items-center py-5">
            <div class="container-xl">
                <div class="row justify-content-center">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="bg-white shadow-lg border-0 rounded rounded-2 border-light p-4 p-lg-5 w-100">
                            <div class="text-center text-md-center mb-4 mt-md-0">
                                <img style="width: 350px;" src="assets/img/brand/communiSync.png" alt="" />
                            </div>
                            <form action="auth/license.register.php" method="POST" class="row mt-4" id="form-row">
                                <!-- Form -->
                                <div class="col-12">
                                    <div class="form-group mb-4">
                                        <label for="license_key">License Key*</label>
                                        <div class="input-group shadow rounded rounded-2">
                                            <span class="input-group-text" id="basic-addon1">
                                                <svg class="icon icon-xs text-gray-600" viewBox="0 0 24 24" width="24"
                                                    height="24" stroke="currentColor" stroke-width="2" fill="none"
                                                    stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                                    <path
                                                        d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4">
                                                    </path>
                                                </svg>
                                            </span>
                                            <input type="text" class="form-control fw-bold"
                                                placeholder="XXXXX-XXXXX-XXXXX-XXXXX" name="license_key"
                                                id="license_key" required autofocus />
                                        </div>
                                        <input type="hidden" name="productKey" id="product_key" value="T1XC8I6V" />
                                        <div id="licenseKeyFeedback" class="form-text"></div>
                                    </div>
                                    <div class="license_button text-center">
                                        <button class="btn btn-secondary bg-gradient px-5" type="button"
                                            id="checkLicense">
                                            Check
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="d-flex justify-content-center align-items-center mt-4">
                                <span class="fw-normal">
                                    Already registered?
                                    <a style="text-decoration: underline;" href="Login" class="fw-bold">Login Here</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include('temp/script.temp.php'); ?>
    <script>
    <?php if (isset($_GET['m'])) { ?>
    <?php if ($_GET['m'] == "missing_data") { ?>
    notify("error", "Missing Details, Click Check After Entering License Key ...");
    <?php } ?>
    <?php } ?>
    $(document).ready(function() {
        $('#checkLicense').click(function() {
            // Get the value from the license key field
            var licenseKey = $('#license_key').val();
            var productKey = $('#product_key').val();

            // Replace the "Check" button with a loading spinner
            $('#checkLicense').prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="ms-1">Loading...</span>'
            );

            // Perform an Ajax request to check the license key
            $.ajax({
                url: 'auth/license.validate.php', // Replace with your PHP file path for license key validation
                type: 'POST',
                data: {
                    licenseKey: licenseKey,
                    productKey: productKey
                },
                success: function(response) {
                    var jsonData = JSON.parse(response);
                    // Update the feedback message based on the validation result
                    if (jsonData.status === 'valid') {
                        $('#licenseKeyFeedback').text('License Key is Valid ...');
                        $('#licenseKeyFeedback').removeClass("text-danger");
                        $('#licenseKeyFeedback').addClass("text-success");
                        $("#form-row").append(jsonData.htmlContent);
                        $('#checkLicense').prop('disabled', true).text('Check');
                        $('#license_key').prop('readonly', true).text('Check');
                        notify("success", "License Key is Valid ...");

                    } else if (jsonData.status === 'registered') {
                        $('#licenseKeyFeedback').text(
                            'License Key is Already Registered ...');
                        $('#licenseKeyFeedback').removeClass("text-success");
                        $('#licenseKeyFeedback').addClass("text-warning");
                        $('#checkLicense').prop('disabled', false).text('Check');
                        $('#license_key').prop('readonly', false).text('Check');
                        notify("error", "License Key is Already Registered ...");

                    } else if (jsonData.status === 'expire') {
                        $('#licenseKeyFeedback').text('License Key is Expired ...');
                        $('#licenseKeyFeedback').removeClass("text-success");
                        $('#licenseKeyFeedback').addClass("text-danger");
                        $('#checkLicense').prop('disabled', false).text('Check');
                        $('#license_key').prop('readonly', false).text('Check');
                        notify("error", "License Key is Expired ...");

                    } else if (jsonData.status === 'invalid') {
                        $('#licenseKeyFeedback').text('License Key is Invalid ...');
                        $('#licenseKeyFeedback').removeClass("text-success");
                        $('#licenseKeyFeedback').addClass("text-danger");
                        $('#checkLicense').prop('disabled', false).text('Check');
                        $('#license_key').prop('readonly', false).text('Check');
                        notify("error", "License Key is Invalid ...");

                    } else {
                        $('#licenseKeyFeedback').text('No License Key is Provided ...');
                        $('#licenseKeyFeedback').removeClass("text-success");
                        $('#licenseKeyFeedback').addClass("text-danger");
                        $('#checkLicense').prop('disabled', false).text('Check');
                        $('#license_key').prop('readonly', false).text('Check');
                        notify("error", "No License Key is Provided ...");

                    }
                },
                error: function() {
                    $('#licenseKeyFeedback').text('An error occurred. Please try again.');
                },
                // complete: function() {
                //     console.log("Ajax Completed ...");
                // }
            });
        });

        $('#form-row').submit(function(e) {
            e.preventDefault();

            var username = $('#username').val();
            var password = $('#password').val();
            var confirmPassword = $('#confirm_password').val();

            // Validate if the passwords match
            if (password !== confirmPassword) {
                $('#confirm_password_feedback').text('Password do not match ...');
                $('#confirm_password_feedback').removeClass('text-success');
                $('#confirm_password_feedback').addClass('text-danger');
                notify("error", "Password do not match ...");
                return;
            } else {
                $('#confirm_password_feedback').removeClass('text-danger');
                $('#confirm_password_feedback').addClass('text-success');
            }

            // Perform Ajax request to check if username and password are valid
            $.ajax({
                url: 'auth/credentials.validate.php', // Replace with the path to your PHP validation file
                type: 'POST',
                data: {
                    username: username
                },
                success: function(response) {
                    // console.log(response);
                    if (response === 'valid') {
                        // Registration is valid, handle form submission
                        $('#username').removeClass('is-invalid');
                        $('#username_feedback').removeClass('text-danger');
                        $('#username_feedback').text('');
                        $('#form-row').off('submit').submit();
                    } else if (response === "loggedIn") {
                        location.reload();
                    } else {
                        $('#username').addClass('is-invalid');
                        notify("error", "Username Already Registered ...");
                        $('#username_feedback').text('Username Already Registered ...');
                        $('#username_feedback').addClass('text-danger');
                    }
                },
                error: function() {
                    console.log('An error occurred. Please try again.');
                }
            });
        });
    });
    </script>

</body>

</html>