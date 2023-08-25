<?php
session_start();
#################### Login & License Validation ####################
require("temp/validate.login.temp.php");                           #
$license_path = "licenses/".$_SESSION['license_username']."/license.json"; #
require("auth/license.validate.functions.php");                    #
require("temp/validate.license.temp.php");                         #
#################### Login & License Validation ####################

####################### Database Connection #######################
require("auth/config.php");                                       #
require("auth/functions.php");                                    #
$conn = conn("localhost", "root", "", "pine-valley");                   #
####################### Database Connection #######################

$query = "SELECT * FROM `activity` WHERE `project_id` = '".$_SESSION['project']."' ORDER BY `id` DESC;";
$activities = fetch_Data($conn, $query);

$query = "SELECT * FROM `users` WHERE `project_id` = '".$_SESSION['project']."';";
$users = fetch_Data($conn, $query);


$title = "Activity";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('temp/head.temp.php'); ?>
</head>

<body>
    <?php include('temp/aside.temp.php'); ?>
    <main class="content">
        <?php include('temp/nav.temp.php'); ?>

        <?php
        ################################ Role Validation ################################
        if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-activity") === true) {
        ################################ Role Validation ################################
        ?>
        <div class="py-3">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="#">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewbox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="#">Activity</a>
                    </li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between w-100 flex-wrap align-items-center">
                <h1 class="h4 mb-0">Activity</h1>
            </div>
        </div>

        <div style="row-gap: 20px;" class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-centered table-hover table-nowrap mb-0 rounded align-items-center" id="datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0 rounded-start">#</th>
                                    <th class="border-0">User</th>
                                    <th class="border-0">Activity</th>
                                    <th class="border-0 text-end rounded-end">Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($activities)){
                                foreach ($activities as $key => $activity) { ?>
                                <tr>
                                    <td class="fw-bolder">
                                        <?=$key+1?>
                                    </td>
                                    <td>
                                        <a class="d-flex align-items-center">
                                            <img src="uploads/profiles/<?php foreach ($users as $user) {
                                                if ($activity['UI'] == $user['u_id']) {
                                                    echo $user['img'];
                                                }
                                            } ?>"
                                                class="avatar rounded-circle me-3" alt="Avatar">
                                            <div class="d-block">
                                                <span class="fw-bold">
                                                    <?php foreach ($users as $user) {
                                                        if ($activity['UI'] == $user['u_id']) {
                                                            if ($user['u_id'] == $_SESSION['id']) {
                                                                echo "<strong class='text-primary'>".$user['f_name'] . " (Me)"."</strong>";
                                                            } else {
                                                                echo $user['f_name'] . " " . $user['s_name'];
                                                            }
                                                        }
                                                    } ?>
                                                </span>
                                                <div class="small text-gray">
                                                <?php foreach ($users as $user) {
                                                        if ($activity['UI'] == $user['u_id']) {
                                                            echo $user['email'];
                                                        }
                                                    } ?>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td><?=$activity['message']?></td>
                                    <td class="text-end">
                                        <span>
                                            <?=date("d M, Y", strtotime($activity['created_date']))?>
                                        </span>
                                        <strong>
                                            <?=date("h:i a", strtotime($activity['created_date']))?>
                                        </strong>
                                    </td>
                                </tr>
                                <?php } } else { ?>
                                <tr>
                                    <td class="fw-bold text-center" colspan="3">No Activity.</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php include('temp/footer.temp.php'); ?>
        <?php } else { ?>
        <div style="height: 80vh;" class="position-relative">
            <img id="accessDenied" class="position-absolute start-0 end-0 top-0 bottom-0 m-auto"
                src="assets/img/access-blocked.png" alt="" height="460" />
        </div>
        <?php } ?>

    </main>
    <?php include('temp/script.temp.php'); ?>

    <script>
    var dummyTable = d.getElementById('datatable');
    if (dummyTable) {
        const dataTable = new simpleDatatables.DataTable(dummyTable, {});
    }
    </script>
</body>

</html>