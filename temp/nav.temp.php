<nav class="navbar navbar-top navbar-expand navbar-dashboard navbar-dark ps-0 pe-2 pb-0">
    <div class="container-fluid px-0">
        <div class="d-flex justify-content-between w-100" id="navbarSupportedContent">
            <div class="d-flex align-items-center">
                <button id="sidebar-toggle"
                    class="sidebar-toggle me-3 btn btn-icon-only d-none d-lg-inline-block align-items-center justify-content-center">
                    <svg class="toggle-icon" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <ul class="navbar-nav align-items-center">
                <?php
                ################################ Role Validation ################################
                if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-activity") === true) { #
                ################################ Role Validation ################################
                ?>
                <li class="nav-item dropdown">
                    <a class="nav-link text-dark notification-bell unread dropdown-toggle"
                        data-unread-notifications="true" href="#" role="button" data-bs-toggle="dropdown"
                        data-bs-display="static" aria-expanded="false">
                        <svg class="icon icon-sm text-gray-900" fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z">
                            </path>
                        </svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-center mt-2 py-0">
                        <div class="list-group list-group-flush" id="notifications"></div>
                    </div>
                </li>
                <?php } ?>
                <li class="nav-item dropdown ms-lg-3">
                    <a class="nav-link dropdown-toggle pt-1 px-0" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <div class="media d-flex align-items-center">
                            <img class="avatar rounded-circle" alt="Image placeholder" src="uploads/profiles/<?=$_SESSION['img']?>">
                            <div class="media-body ms-2 text-dark align-items-center d-none d-lg-block">
                                <span class="mb-0 font-small fw-bold text-gray-900">
                                    <?= $_SESSION['name'] ?>
                                </span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dashboard-dropdown dropdown-menu-end mt-2 py-1">
                        <a class="dropdown-item d-flex align-items-center" href="settings.php">
                            <!-- profile.php -->
                            <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewbox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            My Profile
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewbox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562C15.802 8.249 16 9.1 16 10zm-5.165 3.913l1.58 1.58A5.98 5.98 0 0110 16a5.976 5.976 0 01-2.516-.552l1.562-1.562a4.006 4.006 0 001.789.027zm-4.677-2.796a4.002 4.002 0 01-.041-2.08l-.08.08-1.53-1.533A5.98 5.98 0 004 10c0 .954.223 1.856.619 2.657l1.54-1.54zm1.088-6.45A5.974 5.974 0 0110 4c.954 0 1.856.223 2.657.619l-1.54 1.54a4.002 4.002 0 00-2.346.033L7.246 4.668zM12 10a2 2 0 11-4 0 2 2 0 014 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Support
                        </a>
                        <div role="separator" class="dropdown-divider my-1"></div>
                        <a class="dropdown-item d-flex align-items-center" href="auth/logout.php">
                            <svg class="dropdown-icon text-danger me-2" fill="none" stroke="currentColor"
                                viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php
$timeInterval = remainingTime($license_json['expiration_date']);
$components = explode(", ", $timeInterval);
$totalDays = 0;
$totalHours = 0;
$totalMinutes = 0;

foreach ($components as $component) {
    if (strpos($component, "days") !== false) {
        $totalDays = (int) strtok($component, " days");
    } elseif (strpos($component, "hours") !== false) {
        $totalHours = (int) strtok($component, " hours");
    } elseif (strpos($component, "mins") !== false) {
        $totalMinutes = (int) strtok($component, " mins");
    }
}

$totalTimeInMinutes = ($totalDays * 24 * 60) + ($totalHours * 60) + $totalMinutes;
$sevenDaysInMinutes = 7 * 24 * 60;
if ($totalTimeInMinutes < $sevenDaysInMinutes) {
    ?>
<div class="alert bg-danger bg-gradient text-white my-3 text-center" role="alert">
    <strong>Warning!</strong> License will be deactivated in
    <?= remainingTime($license_json['expiration_date']) ?>.
</div>
<?php } ?>