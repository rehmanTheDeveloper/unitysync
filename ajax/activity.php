<?php

session_start();
#################### Login & License Validation ####################
require("../temp/validate.login.temp.php"); #
$license_path = "../licenses/" . $_SESSION['license_username'] . "/license.json"; #
require("../auth/license.validate.functions.php"); #
require("../temp/validate.license.temp.php"); #
#################### Login & License Validation ####################

######################## Database Connection #######################
require("../auth/config.php"); #
require("../auth/functions.php"); #
$conn = conn("localhost", "root", "", "pine-valley"); #
######################## Database Connection #######################

$query = "SELECT * FROM `activity` WHERE `project_id` = '" . $_SESSION['project'] . "' ORDER BY `id` DESC LIMIT 5;";
$nav_activities = fetch_Data($conn, $query);
$query = "SELECT `u_id`,`f_name`,`img` FROM `users` WHERE `project_id` = '" . $_SESSION['project'] . "';";
$nav_users = fetch_Data($conn, $query);

echo '<a
class="text-center text-primary fw-bold border-bottom border-light py-3">Notifications</a>';
if (!empty($nav_activities)) {
    foreach ($nav_activities as $key => $value) {
        echo '    <a class="list-group-item list-group-item-action border-bottom">
        <div class="row align-items-center">
            <div class="col-auto">
                <img alt="User" src="uploads/profiles/';
        foreach ($nav_users as $nav_user) {
            if ($value['UI'] == $nav_user['u_id']) {
                echo $nav_user['img'];
            }
        }
        echo '" class="avatar-md rounded">
            </div>
            <div class="col ps-0 ms-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="h6 mb-0 text-small text-capitalize">';
        foreach ($nav_users as $nav_user) {
            if ($value['UI'] == $nav_user['u_id']) {
                if ($nav_user['u_id'] == $_SESSION['id']) {
                    echo $nav_user['f_name'] . " (Me)";
                } else {
                    echo $nav_user['f_name'];
                }
            }
        }
        echo '</h4>
            </div>
        <div class="text-end">
            <small class="text-danger">';
        $nav_activity_time = timeInSeconds($value['created_date']);
        $nav_activity_seconds = time_since($nav_activity_time);
        echo $nav_activity_seconds . " ago";
        echo '</small>
                            </div>
                        </div>
                        <p class="font-small mt-1 mb-0">' . $value['message'] . '            </p>
                        </div>
                    </div>
                </a>';
    }
    echo '<a href="activity.php" class="dropdown-item text-center fw-bold rounded-bottom py-3">
            <svg class="icon icon-xxs text-gray-400 me-1" fill="currentColor" viewbox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                <path fill-rule="evenodd"
                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                    clip-rule="evenodd"></path>
            </svg>
            View all
        </a>';
} else {
    echo '<a class="list-group-item list-group-item-action">
            <div class="row align-items-center">
                <div class="col ps-0 ms-2">
                    <p class="font-small fs-6 mt-1 mb-0 fw-bold text-center">
                        No Notifications.
                    </p>
                </div>
            </div>
        </a>';
}
?>