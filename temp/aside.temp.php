<?php
function active($currect_page)
{
    $url_array = explode('/', $_SERVER['REQUEST_URI']);
    $url = end($url_array);
    $filtered_url = explode("?", $url);
    $pages = explode(", ", $currect_page);
    foreach ($pages as $value) {
        if ($value == $filtered_url[0]) {
            echo 'active'; //class name in css
        }
    }
}
function collapse($currect_page)
{
    $url_array = explode('/', $_SERVER['REQUEST_URI']);
    $url = end($url_array);
    $filtered_url = explode("?", $url);
    $pages = explode(", ", $currect_page);
    foreach ($pages as $value) {
        if ($value != $filtered_url[0]) {
            echo 'collapsed'; //class name in css
        }
    }
}
function expand($currect_page)
{
    $url_array = explode('/', $_SERVER['REQUEST_URI']);
    $url = end($url_array);
    $filtered_url = explode("?", $url);
    $pages = explode(", ", $currect_page);
    foreach ($pages as $value) {
        if ($value == $filtered_url[0]) {
            echo 'aria-expanded="true"'; //class name in css
        }
    }
}
function show($currect_page)
{
    $url_array = explode('/', $_SERVER['REQUEST_URI']);
    $url = end($url_array);
    $filtered_url = explode("?", $url);
    $pages = explode(", ", $currect_page);
    foreach ($pages as $value) {
        if ($value == $filtered_url[0]) {
            echo 'show'; //class name in css
        }
    }
}

$query = "SELECT `name` FROM `project` WHERE `pro_id` = '" . $_SESSION['project'] . "';";
$aside_project_name = mysqli_fetch_assoc(mysqli_query($conn, $query));

?>

<nav class="navbar navbar-dark navbar-theme-primary px-4 col-12 d-lg-none">
    <a class="navbar-brand me-lg-5" href="index.php">
        <img class="navbar-brand-dark" src="assets/img/brand/communiSync-logo.png" alt="RTD logo">
        <img class="navbar-brand-light" src="assets/img/brand/communiSync-logo.png" alt="RTD logo">
    </a>
    <div class="d-flex align-items-center">
        <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>
<nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
    <div class="sidebar-inner px-4 pt-3">
        <div class="user-card d-flex d-md-none justify-content-between justify-content-md-center pb-4">
            <div class="d-flex align-items-center">
                <div class="avatar-lg me-4">
                    <img src="uploads/profiles/<?=$_SESSION['img']?>" class="card-img-top rounded-circle border-white"
                        alt="<?= $_SESSION['name'] ?>">
                </div>
                <div class="d-block">
                    <h2 class="h5 mb-3">Hi,
                        <?= $_SESSION['name'] ?>
                    </h2>
                    <a href="auth/logout.php" class="btn btn-secondary d-inline-flex align-items-center">
                        <svg class="icon icon-xxs me-1" fill="none" stroke="currentColor" viewbox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Sign Out
                    </a>
                </div>
            </div>
            <div class="collapse-close d-md-none">
                <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu" aria-expanded="true" aria-label="Toggle navigation">
                    <svg class="icon icon-xs" fill="currentColor" viewbox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </a>
            </div>
        </div>
        <ul class="nav flex-column pt-3 pt-md-0">
            <li class="nav-item">
                <a href="index.php" class="nav-link d-flex align-items-center">
                    <span class="sidebar-icon">
                        <img src="assets/img/brand/communiSync-logo.png" width="30" alt="RTD logo" />
                    </span>
                    <span class="mt-1 sidebar-text">
                        <?= $aside_project_name['name'] ?>
                    </span>
                </a>
            </li>
            <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li>
            <li class="nav-item <?php active('Dashboard'); ?>">
                <a href="Dashboard" class="nav-link d-flex align-items-center justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewbox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                </path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Dashboard</span>
                    </span>
                </a>
            </li>
            <?php
            ################################ Role Validation ################################
            if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-account, edit-account, add-account") === true) { #
                ################################ Role Validation ################################
                ?>
            <li class="nav-item">
                <span class="nav-link <?php collapse('Accounts, account.add.php, account.edit.php');
                    collapse('property.all.php'); ?> d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" data-bs-target="#masterEntry" <?php expand('Accounts');
                        expand('property.all.php'); ?>>
                    <span>
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24">
                                <g data-name="Layer 2">
                                    <g data-name="cloud-upload">
                                        <rect width="24" height="24" opacity="0" />
                                        <path
                                            d="M21.9 12c0-.11-.06-.22-.09-.33a4.17 4.17 0 0 0-.18-.57c-.05-.12-.12-.24-.18-.37s-.15-.3-.24-.44S21 10.08 21 10s-.2-.25-.31-.37-.21-.2-.32-.3L20 9l-.36-.24a3.68 3.68 0 0 0-.44-.23l-.39-.18a4.13 4.13 0 0 0-.5-.15 3 3 0 0 0-.41-.09L17.67 8A6 6 0 0 0 6.33 8l-.18.05a3 3 0 0 0-.41.09 4.13 4.13 0 0 0-.5.15l-.39.18a3.68 3.68 0 0 0-.44.23l-.36.3-.37.31c-.11.1-.22.19-.32.3s-.21.25-.31.37-.18.23-.26.36-.16.29-.24.44-.13.25-.18.37a4.17 4.17 0 0 0-.18.57c0 .11-.07.22-.09.33A5.23 5.23 0 0 0 2 13a5.5 5.5 0 0 0 .09.91c0 .1.05.19.07.29a5.58 5.58 0 0 0 .18.58l.12.29a5 5 0 0 0 .3.56l.14.22a.56.56 0 0 0 .05.08L3 16a5 5 0 0 0 4 2h3v-1.37a2 2 0 0 1-1 .27 2.05 2.05 0 0 1-1.44-.61 2 2 0 0 1 .05-2.83l3-2.9A2 2 0 0 1 12 10a2 2 0 0 1 1.41.59l3 3a2 2 0 0 1 0 2.82A2 2 0 0 1 15 17a1.92 1.92 0 0 1-1-.27V18h3a5 5 0 0 0 4-2l.05-.05a.56.56 0 0 0 .05-.08l.14-.22a5 5 0 0 0 .3-.56l.12-.29a5.58 5.58 0 0 0 .18-.58c0-.1.05-.19.07-.29A5.5 5.5 0 0 0 22 13a5.23 5.23 0 0 0-.1-1z" />
                                        <path
                                            d="M12.71 11.29a1 1 0 0 0-1.4 0l-3 2.9a1 1 0 1 0 1.38 1.44L11 14.36V20a1 1 0 0 0 2 0v-5.59l1.29 1.3a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42z" />
                                    </g>
                                </g>
                            </svg>
                        </span>
                        <span class="sidebar-text">Master Entry</span>
                    </span>
                    <span class="link-arrow">
                        <svg class="icon icon-sm" fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </span>
                </span>
                <div class="multi-level collapse <?php show('Accounts');
                    show('property.all.php'); ?>" role="list" id="masterEntry" aria-expanded="false">
                    <ul class="flex-column nav">
                        <?php
                            ################################ Role Validation ################################
                            if (
                                validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-account") === true || #
                                validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-account") === true || #
                                validationRole($conn, $_SESSION['project'], $_SESSION['role'], "edit-account") === true || #
                                validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-account") === true
                            ) { #
                                ################################ Role Validation ################################
                                ?>
                        <li class="nav-item <?php active('Accounts'); ?>">
                            <a href="Accounts" class="nav-link">
                                <span class="sidebar-text-contracted">
                                    A
                                </span>
                                <span class="sidebar-text">Accounts</span>
                            </a>
                        </li>
                        <?php } ?>
                        <?php
                            ################################ Role Validation ################################
                            if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-property") === true) { #
                                ################################ Role Validation ################################
                                ?>
                        <li class="nav-item <?php active('property.all.php'); ?>">
                            <a href="property.all.php" class="nav-link">
                                <span class="sidebar-text-contracted">
                                    P
                                </span>
                                <span class="sidebar-text">Property</span>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </li>
            <?php } ?>
            <?php
            ################################ Role Validation ################################
            if (
                validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-sale-property") == true || #
                validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-transfer-property") == true || #
                validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-return-property") == true || #
                validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-payment-pay") == true || #
                validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-payment-recieve") == true || #
                validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-payment-transfer") == true
            ) { #
                ################################ Role Validation ################################
                ?>
            <li class="nav-item">
                <span class="nav-link 
                <?php collapse('sale.all.php');
                collapse('transfer.all.php');
                collapse('return.all.php');
                collapse('payment.paid.php');
                collapse('payment.received.php');
                collapse('payment.transfered.php');
                collapse('#'); ?> d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                    data-bs-target="#transactions" <?php expand('sale.all.php');
                        expand('transfer.all.php');
                        expand('return.all.php');
                        expand('payment.paid.php');
                        expand('payment.received.php');
                        expand('payment.transfered.php');
                        expand('#'); ?>>
                    <span>
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                <path fill-rule="evenodd"
                                    d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Transactions</span>
                    </span>
                    <span class="link-arrow">
                        <svg class="icon icon-sm" fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </span>
                </span>
                <div class="multi-level collapse <?php show('sale.all.php');
                    show('transfer.all.php');
                    show('return.all.php');
                    show('payment.paid.php');
                    show('payment.received.php');
                    show('payment.transfered.php');
                    show('#'); ?>" role="list" id="transactions" aria-expanded="false">
                    <ul class="flex-column nav">
                        <?php
                            ################################ Role Validation ################################
                            if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-sale-property") === true) { #
                                ################################ Role Validation ################################
                                ?>
                        <li class="nav-item <?php active('sale.all.php'); ?>">
                            <a href="sale.all.php" class="nav-link">
                                <span class="sidebar-text-contracted">SL</span>
                                <span class="sidebar-text">Sale Property</span>
                            </a>
                        </li>
                        <?php } ?>
                        <?php
                            ################################ Role Validation ################################
                            if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-transfer-property") === true) { #
                                ################################ Role Validation ################################
                                ?>
                        <li class="nav-item <?php active('transfer.all.php'); ?>">
                            <a href="transfer.all.php" class="nav-link">
                                <span class="sidebar-text-contracted">TP</span>
                                <span class="sidebar-text">Transfer Property</span>
                            </a>
                        </li>
                        <?php } ?>
                        <?php
                            ################################ Role Validation ################################
                            if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-return-property") === true) { #
                                ################################ Role Validation ################################
                                ?>
                        <li class="nav-item <?php active('return.all.php'); ?>">
                            <a href="return.all.php" class="nav-link">
                                <span class="sidebar-text-contracted">RP</span>
                                <span class="sidebar-text">Return Property</span>
                            </a>
                        </li>
                        <?php } ?>
                        <?php
                            ################################ Role Validation ################################
                            if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-payment-pay") === true) { #
                                ################################ Role Validation ################################
                                ?>
                        <li class="nav-item <?php active('payment.paid.php'); ?>">
                            <a href="payment.paid.php" class="nav-link">
                                <span class="sidebar-text-contracted">PP</span>
                                <span class="sidebar-text">Payment Paid</span>
                            </a>
                        </li>
                        <?php } ?>
                        <?php
                            ################################## Role Validation ##################################
                            if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-payment-receive") === true) { #
                                ################################## Role Validation ##################################
                                ?>
                        <li class="nav-item <?php active('payment.received.php'); ?>">
                            <a href="payment.received.php" class="nav-link">
                                <span class="sidebar-text-contracted">PR</span>
                                <span class="sidebar-text">Payment Received</span>
                            </a>
                        </li>
                        <?php } ?>
                        <?php
                            ################################## Role Validation ##################################
                            if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-payment-transfer") === true) { #
                                ################################## Role Validation ##################################
                                ?>
                        <li class="nav-item <?php active('payment.transfered.php'); ?>">
                            <a href="payment.transfered.php" class="nav-link">
                                <span class="sidebar-text-contracted">PT</span>
                                <span class="sidebar-text">Payment Transfer</span>
                            </a>
                        </li>
                        <?php } ?>
                        <?php
                            ################################ Role Validation ################################
                            if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-campaign") === true) { #
                                ################################ Role Validation ################################
                                ?>
                        <li class="nav-item <?php active('#'); ?>">
                            <a href="#" class="nav-link">
                                <span class="sidebar-text-contracted">C</span>
                                <span class="sidebar-text">Campaign</span>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </li>
            <?php } ?>
            <?php
            ################################################ Role Validation ################################################
            if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-user, edit-user, view-user, add-user-role, edit-user-role, view-user-role") == true) {
            ################################################ Role Validation ################################################
            ?>
            <li class="nav-item">
                <span
                    class="nav-link 
                    <?php
                    collapse('project.view.php, project.edit.php');
                    collapse('#');
                    collapse('user.config.php, user.all.php, user.view.php, user.edit.php, user.role.add.php, user.role.edit.php, user.role.all.php'); ?> d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" data-bs-target="#configuration"
                    <?php
                        expand('project.view.php, project.edit.php');
                        expand('#');
                        expand('user.config.php, user.all.php, user.view.php, user.edit.php, user.role.add.php, user.role.edit.php, user.role.all.php'); ?>>
                    <span>
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Configuration</span>
                    </span>
                    <span class="link-arrow">
                        <svg class="icon icon-sm" fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </span>
                </span>
                <div class="multi-level collapse 
                <?php
                show('project.view.php, project.edit.php');
                show('#');
                show('user.config.php, user.all.php, user.view.php, user.edit.php, user.role.add.php, user.role.edit.php, user.role.all.php'); ?>"
                    role="list" id="configuration" aria-expanded="false">
                    <ul class="flex-column nav">
                        <?php
                        ################################ Role Validation ################################ 
                        if ($_SESSION['role'] === "super-admin") {
                        ################################ Role Validation ################################
                        ?>
                        <li class="nav-item <?php active('project.view.php, project.edit.php'); ?>">
                            <a href="project.view.php" class="nav-link">
                                <span class="sidebar-text-contracted">P</span>
                                <span class="sidebar-text">Project</span>
                            </a>
                        </li>
                        <?php } ?>
                        <li class="nav-item <?php active('#'); ?>">
                            <a href="#" class="nav-link">
                                <span class="sidebar-text-contracted">S</span>
                                <span class="sidebar-text">Surcharge</span>
                            </a>
                        </li>
                        <?php
                        ################################ Role Validation ################################
                        if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-user, view-user, edit-user, add-user-role, edit-user-role, view-user-role") === true) { #
                        ################################ Role Validation ################################
                        ?>
                        <li
                            class="nav-item <?php active('user.config.php, user.all.php, user.view.php, user.edit.php, user.role.add.php, user.role.edit.php, user.role.all.php'); ?>">
                            <a href="user.all.php" class="nav-link">
                                <span class="sidebar-text-contracted">U</span>
                                <span class="sidebar-text">User Mng.</span>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </li>
            <?php } ?>
            <?php
            ################################################ Role Validation ################################################
            if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-ledger, view-activity") == true) {
            ################################################ Role Validation ################################################
            ?>
            <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li>
            <?php
            ################################################ Role Validation ################################################
            if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-ledger") == true) {
            ################################################ Role Validation ################################################
            ?>
            <li class="nav-item">
                <span
                    class="nav-link <?php collapse('ledger.php'); ?> d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" data-bs-target="#reports" <?php expand('ledger.php'); ?>>
                    <span>
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                <path fill-rule="evenodd"
                                    d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Reports</span>
                    </span>
                    <span class="link-arrow">
                        <svg class="icon icon-sm" fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </span>
                </span>
                <div class="multi-level collapse <?php show('ledger.php'); ?>" role="list" id="reports"
                    aria-expanded="false">
                    <ul class="flex-column nav">
                        <li class="nav-item <?php active('ledger.php'); ?>">
                            <a href="ledger.php" class="nav-link">
                                <span class="sidebar-text-contracted">L</span>
                                <span class="sidebar-text">Ledger</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php } ?>
            <?php
            ################################################ Role Validation ################################################
            if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-activity") == true) {
            ################################################ Role Validation ################################################
            ?>
            <li class="nav-item <?php active('activity.php'); ?>">
                <a href="activity.php" class="nav-link d-flex align-items-center justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Activity</span>
                    </span>
                </a>
            </li>
            <?php } ?>
            <?php } ?>

            <!-- <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li>
            <li class="nav-item">
                <a href="#"
                    target="_blank" class="nav-link d-flex align-items-center">
                    <span class="sidebar-icon">
                        <svg class="icon icon-xs me-2" fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    <span class="sidebar-text">
                        Docs.
                        <span class="badge bg-secondary ms-1 text-gray-800 badge-sm">v1.4</span>
                    </span>
                </a>
            </li> -->
        </ul>
    </div>
</nav>