<?php 
// We need to use sessions
session_start();
// If the user is not logged in redirect to the login page...
if (isset($_SESSION['loggedin'])) {
    header('Location: Dashboard');
    exit;
}


$title = "Sign In";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('temp/head.temp.php'); ?>
</head>

<body>
    <main>
        <section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
            <div class="container">
                <div class="row justify-content-center form-bg-image">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
                            <div class="text-center text-md-center mb-4 mt-md-0">
                                <h1 class="mb-0 h3">Sign In</h1>
                            </div>
                            <form action="auth/login.auth.php" method="post" class="mt-4">
                                <div class="form-group mb-4">
                                    <label for="username">Your Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1">
                                            <svg class="icon icon-xs text-gray-600" fill="currentColor"
                                                viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                                </path>
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z">
                                                </path>
                                            </svg>
                                        </span>
                                        <input type="text" class="form-control" id="username" name="username" autofocus
                                            required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-group mb-4">
                                        <label for="password">Your Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon2">
                                                <svg class="icon icon-xs text-gray-600" fill="currentColor"
                                                    viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                            <input type="password" placeholder="Password" class="form-control"
                                                id="password" name="password" required>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-top mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value id="remember">
                                            <label class="form-check-label mb-0" for="remember">Remember me</label>
                                        </div>
                                        <div>
                                            <!-- <a href="forgot-password.html" class="small text-right">Lost password?</a> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <input type="hidden" name="productKey" value="T1XC8I6V" />
                                    <button type="submit" class="btn btn-gray-800">Sign in</button>
                                </div>
                            </form>
                            <div class="d-flex justify-content-center align-items-center mt-4">
                                <span class="fw-normal">
                                    Have'nt Registered ?
                                    <a href="sign-up.php" class="fw-bold">Register Here</a>
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
    $(function() {
        <?php if (isset($_GET['m'])) { ?>

        <?php if ($_GET['m'] == 'registered') { ?>
            notify("success", "License Key has been registered ...");
        <?php } elseif ($_GET['m'] == 'p_not_exist') { ?>
            notify("error", "Invalid Username ...");
        <?php } elseif ($_GET['m'] == 'p_wrong_pass') { ?>
            notify("error", "Wrong Password ...");
        <?php } elseif ($_GET['m'] == 'l_not_reg') { ?>
            notify("error", "License Key Isn't Registered ...");
        <?php } elseif ($_GET['m'] == 'p_user_inactive') { ?>
            notify("error", "Your Status is Inactive ...");
        <?php } ?>

        <?php } ?>
    });
    </script>
</body>

</html>