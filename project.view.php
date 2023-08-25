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
$conn = conn("localhost", "root", "", "communiSync");             #
####################### Database Connection #######################

if ($_SESSION['role'] !== 'super-admin') {
    header("Location: dashboard.php?message=masti");
    exit();
}

$query = "SELECT * FROM `project` WHERE `pro_id` = '".$_SESSION['project']."';";
$project = mysqli_fetch_assoc(mysqli_query($conn, $query));

// echo "<pre>";
// print_r($project);
// exit();

$query = "SELECT * FROM `area_investor` WHERE `project_id` = '".$_SESSION['project']."';";
$area_investors = fetch_Data($conn, $query);

$query = "SELECT * FROM `investor` WHERE `project_id` = '".$_SESSION['project']."';";
$view_investors = fetch_Data($conn, $query);

$query = "SELECT * FROM `investor` WHERE `acc_id` NOT IN (";
if (!empty($area_investors)) {
    foreach ($area_investors as $key => $area_investor) {
        $area_investors[$key]['total_sqft'] = (($area_investor['kanal'] * 20) * 272.25) + ($area_investor['marla'] * 272.25) + $area_investor['feet'];
        $query .= "'".$area_investor['acc_id']."'";
        if ($key != (count($area_investors) - 1)) {
            $query .= ", ";
        }
    }
} else {
    $query .= "''";
}
$query .= ") AND `project_id` = '".$_SESSION['project']."';";
$insert_investors = fetch_Data($conn, $query);

$query = "SELECT COALESCE(SUM(`kanal`), 0) as `kanal`, COALESCE(SUM(`marla`), 0) as `marla`, COALESCE(SUM(`feet`), 0) as `feet`, COALESCE(SUM(`ratio`), 0) as `ratio` FROM `area_investor` WHERE `project_id` = '".$_SESSION['project']."';";
$total_area_investors = mysqli_fetch_assoc(mysqli_query($conn, $query));

// echo "<pre>";
// print_r($insert_investors);
// exit();

$query = "SELECT * FROM `area_seller` WHERE `project_id` = '".$_SESSION['project']."';";
$area_sellers = fetch_Data($conn, $query);

$query = "SELECT * FROM `seller` WHERE `project_id` = '".$_SESSION['project']."';";
$view_sellers = fetch_Data($conn, $query);

$query = "SELECT * FROM `seller` WHERE `acc_id` NOT IN (";
if (!empty($area_sellers)) {
    foreach ($area_sellers as $key => $area_seller) {
        $query .= "'".$area_seller['acc_id']."'";
        if ($key != (count($area_sellers) - 1)) {
            $query .= ", ";
        }
    }
} else {
    $query .= "''";
}
$query .= ") AND `project_id` = '".$_SESSION['project']."';";
$insert_sellers = fetch_Data($conn, $query);

$query = "SELECT COALESCE(SUM(`kanal`), 0) as `kanal`, COALESCE(SUM(`marla`), 0) as `marla`, COALESCE(SUM(`feet`), 0) as `feet`, COALESCE(SUM(`ratio`), 0) as `ratio` FROM `area_investor` WHERE `project_id` = '".$_SESSION['project']."';";
$total_area_sellers = mysqli_fetch_assoc(mysqli_query($conn, $query));

$title = "Project - ".$project['name'];
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

        <div class="py-3">
            <nav aria-label="breadcrumb" class="d-none d-md-flex justify-content-between align-items-center">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent mb-0">
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
                        <a href="#">Configuration</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Project - <?=$project['name']?>
                    </li>
                </ol>
                <div class="btn-group">
                    <a class="btn btn-outline-gray-800" data-bs-toggle="modal" data-bs-target="#addSeller">
                        Add Seller
                    </a>
                    <a class="btn btn-outline-gray-800" data-bs-toggle="modal" data-bs-target="#addInvestor">
                        Add Investor
                    </a>
                    <a href="project.edit.php?id=<?=$project['id']?>" class="btn btn-outline-gray-800">
                        Edit Project
                    </a>
                </div>
            </nav>
        </div>

        <div style="row-gap: 20px;" class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h2 class="text-center mb-3">Main Details</h2>
                            </div>
                            <div class="col-12">
                                <table class="table table-centered table-nowrap mb-0 rounded">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-0 rounded-start">Label</th>
                                            <th class="border-0 rounded-end text-end">Detail
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bolder">
                                        <tr>
                                            <td>Category</td>
                                            <td class="text-end text-capitalize">
                                                <?=str_replace("-"," ",$project['category'])?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td class="text-end">
                                                <?=$project['address']?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>City</td>
                                            <td class="text-end text-capitalize">
                                                <?=$project['city']?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Country</td>
                                            <td class="text-end text-capitalize">
                                                <?=$project['country']?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Phone No.</td>
                                            <td class="text-end">
                                                <?="+92 ".phone_no_format($project['phone_no'])?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Whatsapp No.</td>
                                            <td class="text-end text-uppercase">
                                                <?=(!empty($project['whatsapp_no']))?"+92 ".phone_no_format($project['whatsapp_no']):"--- no whatsapp ---"?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Helpline No.</td>
                                            <td class="text-end text-uppercase">
                                                <?=(!empty($project['helpline_no']))?"+92 ".phone_no_format($project['helpline_no']):"--- no helpline ---"?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Website</td>
                                            <td class="text-end text-normal">
                                                <?=(!empty($project['website']))?$project['website']:"--- NO WEBSITE ---"?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h2 class="text-center mb-3">Area Details</h2>
                            </div>
                            <div class="col-12">
                                <table class="table table-centered table-nowrap mb-0 rounded">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-0 rounded-start">Label</th>
                                            <th class="border-0 rounded-end text-end">Detail
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bolder">
                                        <tr>
                                            <td>Kanal</td>
                                            <td class="text-end">
                                                <span data-bs-toggle="tooltip"
                                                    data-bs-original-title="<?=number_format($total_area_investors['kanal'])?> kanals.">
                                                    <?=number_format($total_area_investors['kanal'])?> k.
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Marla</td>
                                            <td class="text-end">
                                                <span data-bs-toggle="tooltip"
                                                    data-bs-original-title="<?=number_format($total_area_investors['marla'])?> marlas.">
                                                    <?=number_format($total_area_investors['marla'])?> m.
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Feet</td>
                                            <td class="text-end">
                                                <span data-bs-toggle="tooltip"
                                                    data-bs-original-title="<?=number_format($total_area_investors['feet'])?> feets.">
                                                    <?=number_format($total_area_investors['feet'])?> Ft.
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Total Area Sqft.</td>
                                            <td class="text-end">
                                                <?=number_format((($total_area_investors['kanal'] * 20) * 272.25) + ($total_area_investors['marla'] * 272.25) + $total_area_investors['feet'])?>
                                                Sqft.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Commercial SqFt</td>
                                            <td class="text-end">
                                                <?=number_format($project['commercial_sqft'])?> Sqft.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Residential SqFt</td>
                                            <td class="text-end">
                                                <?=number_format($project['residential_sqft'])?> Sqft.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Wastage SqFt</td>
                                            <td class="text-end">
                                                <?=number_format(((($total_area_investors['kanal'] * 20) * 272.25) + ($total_area_investors['marla'] * 272.25) + $total_area_investors['feet']) - ($project['commercial_sqft'] + $project['residential_sqft']))?>
                                                Sqft.
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card h-100">
                    <div class="card-header p-3">
                        <h2 class="mb-0 text-center">Area SqFt.</h2>
                    </div>
                    <div class="card-body d-flex justify-content-center align-items-center flex-column">
                        <?php if (($project['commercial_sqft'] + $project['residential_sqft']) > 0) { ?>
                        <div class="w-100" id="totalSqft"></div>
                        <?php } else { ?>
                        <svg width="126.795" height="129.387">
                            <g data-name="Group 146" transform="translate(-64.456)">
                                <circle data-name="Ellipse 161" cx="47.358" cy="47.358" r="47.358"
                                    transform="translate(96.534)" fill="#3f3d56"></circle>
                                <circle data-name="Ellipse 162" cx="39.096" cy="39.096" r="39.096"
                                    transform="translate(104.797 8.262)" opacity="0.05"></circle>
                                <circle data-name="Ellipse 163" cx="32.042" cy="32.042" r="32.042"
                                    transform="translate(111.85 15.316)" opacity="0.05"></circle>
                                <circle data-name="Ellipse 164" cx="22.974" cy="22.974" r="22.974"
                                    transform="translate(120.918 24.384)" opacity="0.05"></circle>
                                <path data-name="Path 630"
                                    d="M67.653 51.596s-3.723 10.48-2.069 14.2a46.238 46.238 0 004.275 7.309s-.965-20.819-2.206-21.509z"
                                    fill="#d0cde1"></path>
                                <path data-name="Path 631"
                                    d="M67.653 51.596s-3.723 10.48-2.069 14.2a46.238 46.238 0 004.275 7.309s-.965-20.819-2.206-21.509z"
                                    opacity="0.1"></path>
                                <path data-name="Path 632"
                                    d="M70.273 75.867a22.35 22.35 0 01-.414 2.758c-.138.138.138.414 0 .827s-.276.965 0 1.1-1.517 12.273-1.517 12.273-4.413 5.792-2.62 14.893l.552 9.239s4.275.276 4.275-1.241a25.264 25.264 0 01-.276-2.62c0-.827.689-.827.276-1.241s-.414-.689-.414-.689.689-.552.552-.69 1.241-9.929 1.241-9.929 1.517-1.517 1.517-2.344v-.827s.689-1.793.689-1.931 3.723-8.55 3.723-8.55l1.517 6.068 1.655 8.688s.827 7.86 2.482 10.894c0 0 2.9 9.929 2.9 9.653s4.826-.965 4.689-2.206-2.9-18.617-2.9-18.617l.692-25.784z"
                                    fill="#2f2e41"></path>
                                <path data-name="Path 633"
                                    d="M66.55 116.273s-3.723 7.309-1.241 7.585 3.448.276 4.551-.827a18.354 18.354 0 012.808-2.022 3.631 3.631 0 001.724-3.455c-.073-.675-.325-1.231-.945-1.282a8.47 8.47 0 01-3.585-1.655z"
                                    fill="#2f2e41"></path>
                                <path data-name="Path 634"
                                    d="M87.097 121.649s-3.723 7.309-1.241 7.585 3.448.276 4.551-.827a18.356 18.356 0 012.808-2.022 3.631 3.631 0 001.723-3.453c-.073-.675-.325-1.231-.945-1.282a8.472 8.472 0 01-3.585-1.655z"
                                    fill="#2f2e41"></path>
                                <circle data-name="Ellipse 165" cx="5.797" cy="5.797" r="5.797"
                                    transform="translate(77.365 28.042)" fill="#ffb8b8"></circle>
                                <path data-name="Path 635"
                                    d="M79.436 35.743s-4.141 7.619-4.472 7.619 7.453 2.484 7.453 2.484 2.153-7.287 2.484-7.95z"
                                    fill="#ffb8b8"></path>
                                <path data-name="Path 636"
                                    d="M85.788 44.081s-8.274-4.551-9.1-4.413-9.653 7.86-9.515 11.032a68.162 68.162 0 001.241 8.412s.414 14.617 1.241 14.755-.138 2.62.138 2.62 19.306 0 19.444-.414-3.449-31.992-3.449-31.992z"
                                    fill="#d0cde1"></path>
                                <path data-name="Path 637" d="M90.407 76.832s2.62 8 .414 7.722-3.172-6.895-3.172-6.895z"
                                    fill="#ffb8b8"></path>
                                <path data-name="Path 638"
                                    d="M83.374 43.598s-5.1 1.1-4.275 8 2.344 13.79 2.344 13.79l5.1 11.17.552 2.069 3.723-.965-2.758-16s-.965-17.1-2.206-17.651a5.341 5.341 0 00-2.48-.413z"
                                    fill="#d0cde1"></path>
                                <path data-name="Path 639" d="M80.271 65.182l6.343 11.308-5.343-11.918z" opacity="0.1">
                                </path>
                                <path data-name="Path 640"
                                    d="M88.937 32.132l.019-.443.881.219a.985.985 0 00-.395-.725l.939-.052a10.128 10.128 0 00-6.774-4.186 6.47 6.47 0 00-5.683 1.638 6.85 6.85 0 00-1.4 2.609c-.556 1.746-.669 3.828.49 5.248 1.178 1.443 3.236 1.725 5.09 1.9a4.019 4.019 0 001.941-.132 4.668 4.668 0 00-.26-2.048 1.365 1.365 0 01-.138-.652c.082-.552.818-.691 1.371-.616s1.217.189 1.58-.235a1.878 1.878 0 00.269-1.1c.085-1.035 2.057-1.206 2.07-1.425z"
                                    fill="#2f2e41"></path>
                            </g>
                        </svg>
                        <h4 class="mt-2">No Results.</h4>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h2 class="text-center mb-3">All Sellers</h2>
                        <table class="table table-centered table-hover table-nowrap mb-0 rounded" id="">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0 rounded-start">Sr.</th>
                                    <th class="border-0">Seller Name</th>
                                    <th class="border-0 text-center">Kanal</th>
                                    <th class="border-0 text-center">Marla</th>
                                    <th class="border-0 text-center">Feet</th>
                                    <th class="border-0 text-center">Total Amount</th>
                                    <th class="border-0 text-end">Payment Period</th>
                                    <th class="border-0 rounded-end text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(TRUE){
                                        for ($i = 0; $i < 4; $i++) { ?>
                                <tr>
                                    <td class="fw-bolder">
                                        <?=$i+1?>
                                    </td>
                                    <td>Ali Abdullah</td>
                                    <td class="fw-bold text-center">3</td>
                                    <td class="fw-bold text-center">16</td>
                                    <td class="fw-bold text-center">98</td>
                                    <td class="fw-bold text-center" data-bs-toggle="tooltip"
                                        data-bs-original-title="Rs. <?=number_format("45000000")?>">Rs.
                                        <?=number_format_thousands("45000000")?></td>
                                    <td class="fw-bold text-end">36 months</td>
                                    <td style="column-gap: 15px;" class="d-flex justify-content-end align-items-center">
                                        <button
                                            class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z">
                                                </path>
                                            </svg>
                                            <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1"
                                            style="">
                                            <a class="dropdown-item d-flex align-items-center" data-bs-toggle="modal"
                                                data-bs-target="#editSeller">
                                                <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z">
                                                    </path>
                                                    <path fill-rule="evenodd"
                                                        d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Edit
                                            </a>
                                            <a class="dropdown-item d-flex align-items-center" href="docs.view.php">
                                                <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path>
                                                    <path fill-rule="evenodd"
                                                        d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Docs
                                            </a>
                                            <a class="dropdown-item d-flex align-items-center" href="account.view.php">
                                                <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                    <path fill-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                View
                                            </a>
                                        </div>
                                        <div class="btn-group">
                                            <button class="btn btn-link dropdown-toggle dropdown-toggle-split m-0 p-0"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                title="Delete">
                                                <svg class="icon icon-xs text-danger" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1"
                                                style="">
                                                <a class="dropdown-item d-flex align-items-center" href="#">
                                                    <svg class="icon icon-xs dropdown-icon text-success me-2"
                                                        fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Yes
                                                </a>
                                                <a class="dropdown-item d-flex align-items-center"
                                                    data-bs-dismiss="dropdown">
                                                    <svg class="icon icon-xs dropdown-icon text-danger me-2"
                                                        fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    <i class="fa-solid fa-circle-xmark fs-5"></i>
                                                    No
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td class="fw-bolder">
                                        Total:
                                    </td>
                                    <td class="border-0"></td>
                                    <td class="fw-bold text-center">12</td>
                                    <td class="fw-bold text-center">64</td>
                                    <td class="fw-bold text-center">392</td>
                                    <td class="fw-bold text-center">Rs. 180,000,000</td>
                                    <td class="border-0"></td>
                                    <td class="border-0"></td>
                                </tr>
                                <?php } else { ?>
                                <tr>
                                    <td class="fw-bold text-center" colspan="8">No Seller ...</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h2 class="text-center mb-3">All Investors</h2>
                        <table class="table table-centered table-hover table-nowrap mb-0 rounded" id="">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0 rounded-start">Sr.</th>
                                    <th class="border-0">Investor</th>
                                    <th class="border-0 text-center">Kanal</th>
                                    <th class="border-0 text-center">Marla</th>
                                    <th class="border-0 text-center">Feet</th>
                                    <th class="border-0 rounded-end text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($area_investors)){
                            foreach ($area_investors as $key => $area_investor) { ?>
                                <tr>
                                    <td class="fw-bolder">
                                        <?=$key+1?>
                                    </td>
                                    <td>
                                        <?php 
                                        foreach ($view_investors as $investor) {
                                            if ($area_investor['acc_id'] == $investor['acc_id']) {
                                                echo $investor['name'];
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td class="fw-bold text-center"><?=$area_investor['kanal']?></td>
                                    <td class="fw-bold text-center"><?=$area_investor['marla']?></td>
                                    <td class="fw-bold text-center"><?=$area_investor['feet']?></td>
                                    <td style="column-gap: 15px;" class="d-flex justify-content-end align-items-center">
                                        <button
                                            class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z">
                                                </path>
                                            </svg>
                                            <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1"
                                            style="">
                                            <a class="dropdown-item d-flex align-items-center" data-bs-toggle="modal"
                                                data-bs-target="#editInvestor">
                                                <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z">
                                                    </path>
                                                    <path fill-rule="evenodd"
                                                        d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Edit
                                            </a>
                                            <a class="dropdown-item d-flex align-items-center"
                                                href="docs.view.php?id=<?=$investor['acc_id']?>">
                                                <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path>
                                                    <path fill-rule="evenodd"
                                                        d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Docs
                                            </a>
                                            <a class="dropdown-item d-flex align-items-center" href="account.view.php">
                                                <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                    <path fill-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                View
                                            </a>
                                        </div>
                                        <div class="btn-group">
                                            <button class="btn btn-link dropdown-toggle dropdown-toggle-split m-0 p-0"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                title="Delete">
                                                <svg class="icon icon-xs text-danger" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1"
                                                style="">
                                                <a class="dropdown-item d-flex align-items-center" href="#">
                                                    <svg class="icon icon-xs dropdown-icon text-success me-2"
                                                        fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Yes
                                                </a>
                                                <a class="dropdown-item d-flex align-items-center"
                                                    data-bs-dismiss="dropdown">
                                                    <svg class="icon icon-xs dropdown-icon text-danger me-2"
                                                        fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    <i class="fa-solid fa-circle-xmark fs-5"></i>
                                                    No
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td class="fw-bolder">
                                        Total:
                                    </td>
                                    <td></td>
                                    <td class="fw-bold text-center"><?=$total_area_investors['kanal']?></td>
                                    <td class="fw-bold text-center"><?=$total_area_investors['marla']?></td>
                                    <td class="fw-bold text-center"><?=$total_area_investors['feet']?></td>
                                    <td></td>
                                </tr>
                                <?php } else { ?>
                                <tr>
                                    <td class="fw-bold text-center" colspan="8">No Investor ...</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card h-100">
                    <div class="card-header p-3">
                        <h2 class="mb-0 text-center">Sellers SqFt.</h2>
                    </div>
                    <div class="card-body d-flex justify-content-center align-items-center flex-column">
                        <?php if (!empty($area_sellers)) { ?>
                        <div class="w-100" id="sellerSqft"></div>
                        <?php } else { ?>
                        <svg width="126.795" height="129.387">
                            <g data-name="Group 146" transform="translate(-64.456)">
                                <circle data-name="Ellipse 161" cx="47.358" cy="47.358" r="47.358"
                                    transform="translate(96.534)" fill="#3f3d56"></circle>
                                <circle data-name="Ellipse 162" cx="39.096" cy="39.096" r="39.096"
                                    transform="translate(104.797 8.262)" opacity="0.05"></circle>
                                <circle data-name="Ellipse 163" cx="32.042" cy="32.042" r="32.042"
                                    transform="translate(111.85 15.316)" opacity="0.05"></circle>
                                <circle data-name="Ellipse 164" cx="22.974" cy="22.974" r="22.974"
                                    transform="translate(120.918 24.384)" opacity="0.05"></circle>
                                <path data-name="Path 630"
                                    d="M67.653 51.596s-3.723 10.48-2.069 14.2a46.238 46.238 0 004.275 7.309s-.965-20.819-2.206-21.509z"
                                    fill="#d0cde1"></path>
                                <path data-name="Path 631"
                                    d="M67.653 51.596s-3.723 10.48-2.069 14.2a46.238 46.238 0 004.275 7.309s-.965-20.819-2.206-21.509z"
                                    opacity="0.1"></path>
                                <path data-name="Path 632"
                                    d="M70.273 75.867a22.35 22.35 0 01-.414 2.758c-.138.138.138.414 0 .827s-.276.965 0 1.1-1.517 12.273-1.517 12.273-4.413 5.792-2.62 14.893l.552 9.239s4.275.276 4.275-1.241a25.264 25.264 0 01-.276-2.62c0-.827.689-.827.276-1.241s-.414-.689-.414-.689.689-.552.552-.69 1.241-9.929 1.241-9.929 1.517-1.517 1.517-2.344v-.827s.689-1.793.689-1.931 3.723-8.55 3.723-8.55l1.517 6.068 1.655 8.688s.827 7.86 2.482 10.894c0 0 2.9 9.929 2.9 9.653s4.826-.965 4.689-2.206-2.9-18.617-2.9-18.617l.692-25.784z"
                                    fill="#2f2e41"></path>
                                <path data-name="Path 633"
                                    d="M66.55 116.273s-3.723 7.309-1.241 7.585 3.448.276 4.551-.827a18.354 18.354 0 012.808-2.022 3.631 3.631 0 001.724-3.455c-.073-.675-.325-1.231-.945-1.282a8.47 8.47 0 01-3.585-1.655z"
                                    fill="#2f2e41"></path>
                                <path data-name="Path 634"
                                    d="M87.097 121.649s-3.723 7.309-1.241 7.585 3.448.276 4.551-.827a18.356 18.356 0 012.808-2.022 3.631 3.631 0 001.723-3.453c-.073-.675-.325-1.231-.945-1.282a8.472 8.472 0 01-3.585-1.655z"
                                    fill="#2f2e41"></path>
                                <circle data-name="Ellipse 165" cx="5.797" cy="5.797" r="5.797"
                                    transform="translate(77.365 28.042)" fill="#ffb8b8"></circle>
                                <path data-name="Path 635"
                                    d="M79.436 35.743s-4.141 7.619-4.472 7.619 7.453 2.484 7.453 2.484 2.153-7.287 2.484-7.95z"
                                    fill="#ffb8b8"></path>
                                <path data-name="Path 636"
                                    d="M85.788 44.081s-8.274-4.551-9.1-4.413-9.653 7.86-9.515 11.032a68.162 68.162 0 001.241 8.412s.414 14.617 1.241 14.755-.138 2.62.138 2.62 19.306 0 19.444-.414-3.449-31.992-3.449-31.992z"
                                    fill="#d0cde1"></path>
                                <path data-name="Path 637" d="M90.407 76.832s2.62 8 .414 7.722-3.172-6.895-3.172-6.895z"
                                    fill="#ffb8b8"></path>
                                <path data-name="Path 638"
                                    d="M83.374 43.598s-5.1 1.1-4.275 8 2.344 13.79 2.344 13.79l5.1 11.17.552 2.069 3.723-.965-2.758-16s-.965-17.1-2.206-17.651a5.341 5.341 0 00-2.48-.413z"
                                    fill="#d0cde1"></path>
                                <path data-name="Path 639" d="M80.271 65.182l6.343 11.308-5.343-11.918z" opacity="0.1">
                                </path>
                                <path data-name="Path 640"
                                    d="M88.937 32.132l.019-.443.881.219a.985.985 0 00-.395-.725l.939-.052a10.128 10.128 0 00-6.774-4.186 6.47 6.47 0 00-5.683 1.638 6.85 6.85 0 00-1.4 2.609c-.556 1.746-.669 3.828.49 5.248 1.178 1.443 3.236 1.725 5.09 1.9a4.019 4.019 0 001.941-.132 4.668 4.668 0 00-.26-2.048 1.365 1.365 0 01-.138-.652c.082-.552.818-.691 1.371-.616s1.217.189 1.58-.235a1.878 1.878 0 00.269-1.1c.085-1.035 2.057-1.206 2.07-1.425z"
                                    fill="#2f2e41"></path>
                            </g>
                        </svg>
                        <h4 class="mt-2">No Results.</h4>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card h-100">
                    <div class="card-header p-3">
                        <h2 class="mb-0 text-center">Investors SqFt.</h2>
                    </div>
                    <div class="card-body d-flex justify-content-center align-items-center">
                        <?php if (!empty($area_investors)) { ?>
                        <div class="w-100" id="investorSqft"></div>
                        <?php } else { ?>
                        <svg width="126.795" height="129.387">
                            <g data-name="Group 146" transform="translate(-64.456)">
                                <circle data-name="Ellipse 161" cx="47.358" cy="47.358" r="47.358"
                                    transform="translate(96.534)" fill="#3f3d56"></circle>
                                <circle data-name="Ellipse 162" cx="39.096" cy="39.096" r="39.096"
                                    transform="translate(104.797 8.262)" opacity="0.05"></circle>
                                <circle data-name="Ellipse 163" cx="32.042" cy="32.042" r="32.042"
                                    transform="translate(111.85 15.316)" opacity="0.05"></circle>
                                <circle data-name="Ellipse 164" cx="22.974" cy="22.974" r="22.974"
                                    transform="translate(120.918 24.384)" opacity="0.05"></circle>
                                <path data-name="Path 630"
                                    d="M67.653 51.596s-3.723 10.48-2.069 14.2a46.238 46.238 0 004.275 7.309s-.965-20.819-2.206-21.509z"
                                    fill="#d0cde1"></path>
                                <path data-name="Path 631"
                                    d="M67.653 51.596s-3.723 10.48-2.069 14.2a46.238 46.238 0 004.275 7.309s-.965-20.819-2.206-21.509z"
                                    opacity="0.1"></path>
                                <path data-name="Path 632"
                                    d="M70.273 75.867a22.35 22.35 0 01-.414 2.758c-.138.138.138.414 0 .827s-.276.965 0 1.1-1.517 12.273-1.517 12.273-4.413 5.792-2.62 14.893l.552 9.239s4.275.276 4.275-1.241a25.264 25.264 0 01-.276-2.62c0-.827.689-.827.276-1.241s-.414-.689-.414-.689.689-.552.552-.69 1.241-9.929 1.241-9.929 1.517-1.517 1.517-2.344v-.827s.689-1.793.689-1.931 3.723-8.55 3.723-8.55l1.517 6.068 1.655 8.688s.827 7.86 2.482 10.894c0 0 2.9 9.929 2.9 9.653s4.826-.965 4.689-2.206-2.9-18.617-2.9-18.617l.692-25.784z"
                                    fill="#2f2e41"></path>
                                <path data-name="Path 633"
                                    d="M66.55 116.273s-3.723 7.309-1.241 7.585 3.448.276 4.551-.827a18.354 18.354 0 012.808-2.022 3.631 3.631 0 001.724-3.455c-.073-.675-.325-1.231-.945-1.282a8.47 8.47 0 01-3.585-1.655z"
                                    fill="#2f2e41"></path>
                                <path data-name="Path 634"
                                    d="M87.097 121.649s-3.723 7.309-1.241 7.585 3.448.276 4.551-.827a18.356 18.356 0 012.808-2.022 3.631 3.631 0 001.723-3.453c-.073-.675-.325-1.231-.945-1.282a8.472 8.472 0 01-3.585-1.655z"
                                    fill="#2f2e41"></path>
                                <circle data-name="Ellipse 165" cx="5.797" cy="5.797" r="5.797"
                                    transform="translate(77.365 28.042)" fill="#ffb8b8"></circle>
                                <path data-name="Path 635"
                                    d="M79.436 35.743s-4.141 7.619-4.472 7.619 7.453 2.484 7.453 2.484 2.153-7.287 2.484-7.95z"
                                    fill="#ffb8b8"></path>
                                <path data-name="Path 636"
                                    d="M85.788 44.081s-8.274-4.551-9.1-4.413-9.653 7.86-9.515 11.032a68.162 68.162 0 001.241 8.412s.414 14.617 1.241 14.755-.138 2.62.138 2.62 19.306 0 19.444-.414-3.449-31.992-3.449-31.992z"
                                    fill="#d0cde1"></path>
                                <path data-name="Path 637" d="M90.407 76.832s2.62 8 .414 7.722-3.172-6.895-3.172-6.895z"
                                    fill="#ffb8b8"></path>
                                <path data-name="Path 638"
                                    d="M83.374 43.598s-5.1 1.1-4.275 8 2.344 13.79 2.344 13.79l5.1 11.17.552 2.069 3.723-.965-2.758-16s-.965-17.1-2.206-17.651a5.341 5.341 0 00-2.48-.413z"
                                    fill="#d0cde1"></path>
                                <path data-name="Path 639" d="M80.271 65.182l6.343 11.308-5.343-11.918z" opacity="0.1">
                                </path>
                                <path data-name="Path 640"
                                    d="M88.937 32.132l.019-.443.881.219a.985.985 0 00-.395-.725l.939-.052a10.128 10.128 0 00-6.774-4.186 6.47 6.47 0 00-5.683 1.638 6.85 6.85 0 00-1.4 2.609c-.556 1.746-.669 3.828.49 5.248 1.178 1.443 3.236 1.725 5.09 1.9a4.019 4.019 0 001.941-.132 4.668 4.668 0 00-.26-2.048 1.365 1.365 0 01-.138-.652c.082-.552.818-.691 1.371-.616s1.217.189 1.58-.235a1.878 1.878 0 00.269-1.1c.085-1.035 2.057-1.206 2.07-1.425z"
                                    fill="#2f2e41"></path>
                            </g>
                        </svg>
                        <h4 class="mt-2">No Results.</h4>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card h-100">
                    <div class="card-header p-3">
                        <h2 class="mb-0 text-center">Investors Ratio %</h2>
                    </div>
                    <div class="card-body d-flex justify-content-center align-items-center">
                        <?php if (($project['commercial_sqft'] + $project['residential_sqft']) > 0) { ?>
                        <div class="w-100" id="investorRatio"></div>
                        <?php } else { ?>
                        <svg width="126.795" height="129.387">
                            <g data-name="Group 146" transform="translate(-64.456)">
                                <circle data-name="Ellipse 161" cx="47.358" cy="47.358" r="47.358"
                                    transform="translate(96.534)" fill="#3f3d56"></circle>
                                <circle data-name="Ellipse 162" cx="39.096" cy="39.096" r="39.096"
                                    transform="translate(104.797 8.262)" opacity="0.05"></circle>
                                <circle data-name="Ellipse 163" cx="32.042" cy="32.042" r="32.042"
                                    transform="translate(111.85 15.316)" opacity="0.05"></circle>
                                <circle data-name="Ellipse 164" cx="22.974" cy="22.974" r="22.974"
                                    transform="translate(120.918 24.384)" opacity="0.05"></circle>
                                <path data-name="Path 630"
                                    d="M67.653 51.596s-3.723 10.48-2.069 14.2a46.238 46.238 0 004.275 7.309s-.965-20.819-2.206-21.509z"
                                    fill="#d0cde1"></path>
                                <path data-name="Path 631"
                                    d="M67.653 51.596s-3.723 10.48-2.069 14.2a46.238 46.238 0 004.275 7.309s-.965-20.819-2.206-21.509z"
                                    opacity="0.1"></path>
                                <path data-name="Path 632"
                                    d="M70.273 75.867a22.35 22.35 0 01-.414 2.758c-.138.138.138.414 0 .827s-.276.965 0 1.1-1.517 12.273-1.517 12.273-4.413 5.792-2.62 14.893l.552 9.239s4.275.276 4.275-1.241a25.264 25.264 0 01-.276-2.62c0-.827.689-.827.276-1.241s-.414-.689-.414-.689.689-.552.552-.69 1.241-9.929 1.241-9.929 1.517-1.517 1.517-2.344v-.827s.689-1.793.689-1.931 3.723-8.55 3.723-8.55l1.517 6.068 1.655 8.688s.827 7.86 2.482 10.894c0 0 2.9 9.929 2.9 9.653s4.826-.965 4.689-2.206-2.9-18.617-2.9-18.617l.692-25.784z"
                                    fill="#2f2e41"></path>
                                <path data-name="Path 633"
                                    d="M66.55 116.273s-3.723 7.309-1.241 7.585 3.448.276 4.551-.827a18.354 18.354 0 012.808-2.022 3.631 3.631 0 001.724-3.455c-.073-.675-.325-1.231-.945-1.282a8.47 8.47 0 01-3.585-1.655z"
                                    fill="#2f2e41"></path>
                                <path data-name="Path 634"
                                    d="M87.097 121.649s-3.723 7.309-1.241 7.585 3.448.276 4.551-.827a18.356 18.356 0 012.808-2.022 3.631 3.631 0 001.723-3.453c-.073-.675-.325-1.231-.945-1.282a8.472 8.472 0 01-3.585-1.655z"
                                    fill="#2f2e41"></path>
                                <circle data-name="Ellipse 165" cx="5.797" cy="5.797" r="5.797"
                                    transform="translate(77.365 28.042)" fill="#ffb8b8"></circle>
                                <path data-name="Path 635"
                                    d="M79.436 35.743s-4.141 7.619-4.472 7.619 7.453 2.484 7.453 2.484 2.153-7.287 2.484-7.95z"
                                    fill="#ffb8b8"></path>
                                <path data-name="Path 636"
                                    d="M85.788 44.081s-8.274-4.551-9.1-4.413-9.653 7.86-9.515 11.032a68.162 68.162 0 001.241 8.412s.414 14.617 1.241 14.755-.138 2.62.138 2.62 19.306 0 19.444-.414-3.449-31.992-3.449-31.992z"
                                    fill="#d0cde1"></path>
                                <path data-name="Path 637" d="M90.407 76.832s2.62 8 .414 7.722-3.172-6.895-3.172-6.895z"
                                    fill="#ffb8b8"></path>
                                <path data-name="Path 638"
                                    d="M83.374 43.598s-5.1 1.1-4.275 8 2.344 13.79 2.344 13.79l5.1 11.17.552 2.069 3.723-.965-2.758-16s-.965-17.1-2.206-17.651a5.341 5.341 0 00-2.48-.413z"
                                    fill="#d0cde1"></path>
                                <path data-name="Path 639" d="M80.271 65.182l6.343 11.308-5.343-11.918z" opacity="0.1">
                                </path>
                                <path data-name="Path 640"
                                    d="M88.937 32.132l.019-.443.881.219a.985.985 0 00-.395-.725l.939-.052a10.128 10.128 0 00-6.774-4.186 6.47 6.47 0 00-5.683 1.638 6.85 6.85 0 00-1.4 2.609c-.556 1.746-.669 3.828.49 5.248 1.178 1.443 3.236 1.725 5.09 1.9a4.019 4.019 0 001.941-.132 4.668 4.668 0 00-.26-2.048 1.365 1.365 0 01-.138-.652c.082-.552.818-.691 1.371-.616s1.217.189 1.58-.235a1.878 1.878 0 00.269-1.1c.085-1.035 2.057-1.206 2.07-1.425z"
                                    fill="#2f2e41"></path>
                            </g>
                        </svg>
                        <h4 class="mt-2">No Results.</h4>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addSeller" tabindex="-1" aria-labelledby="addSeller" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <form action="comp/project.seller.add.php" method="post" enctype="multipart/form-data"
                            autocomplete="off" class="card">
                            <div class="modal-header">
                                <h2 class="h6 modal-title">Add Seller</h2>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <?php if (!empty($insert_sellers)) { ?>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="addSellerSeller">Seller</label>
                                            <select class="form-select" name="seller" id="addSellerSeller" required>
                                                <option value="" selected>Select Seller</option>
                                                <?php foreach ($insert_sellers as $key => $seller) { 
                                                    ?>
                                                <option value="<?=$seller['acc_id']?>">
                                                    <?=$seller['name'].", ".cnic_format($seller['cnic'])?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="addSellerAmount">Amount</label>
                                            <input type="text" name="amount" id="addSellerAmount"
                                                class="form-control comma" required />
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="addSellerPeriod">Payment Period (Months)</label>
                                            <input type="text" name="period" id="addSellerPeriod" class="form-control"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label for="addSellerKanal">Kanal</label>
                                            <input type="text" name="kanal" id="addSellerKanal" class="form-control"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label for="addSellerMarla">Marla</label>
                                            <input type="text" name="marla" id="addSellerMarla" class="form-control"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label for="addSellerFeet">Feet</label>
                                            <input type="text" name="feet" id="addSellerFeet" class="form-control"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label class="btn btn-outline-primary w-100" for="sellerDocs">Upload
                                                Document</label>
                                            <input class="form-control" type="file" name="docs[]" id="sellerDocs"
                                                multiple hidden />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row d-none" id="sellerFileUploadProgress">
                                            <div class="col-3">
                                                <button class="btn btn-primary w-100" id="sellerPercent" disabled>
                                                    0%
                                                </button>
                                            </div>
                                            <div class="col-3">
                                                <button class="btn btn-primary w-100" id="sellerDataTransferred"
                                                    disabled>
                                                    Total / Loaded
                                                </button>
                                            </div>
                                            <div class="col-3">
                                                <button class="btn btn-primary w-100" id="sellerMbps" disabled>
                                                    0 Mbps
                                                </button>
                                            </div>
                                            <div class="col-3">
                                                <button class="btn btn-primary w-100" id="sellerTimeLeft" disabled>
                                                    Time Left
                                                </button>
                                            </div>
                                            <div class="col-12 pt-3">
                                                <div class="progress-wrapper">
                                                    <div class="progress progress-xl">
                                                        <div class="progress-bar bg-primary seller-progress-bar"
                                                            role="progressbar" style="width: 0%;" aria-valuenow="25"
                                                            aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 pt-2">
                                                <button class="btn btn-primary w-100" id="sellerCancel" disabled>
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-none" id="sellerSelectedFilesTable">
                                            <table class="table table-centered table-nowrap mb-0 rounded">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="border-0 rounded-start">#</th>
                                                        <th class="border-0 rounded-end text-end">Doc Name
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-bolder">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php } else { ?>
                                    <h4 class="text-center">No Seller Available ...</h4>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link text-gray-600 ms-auto"
                                    data-bs-dismiss="modal">Close
                                </button>
                                <button type="submit" class="btn btn-success text-white fw-bolder">Add Seller</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editSeller" tabindex="-1" aria-labelledby="editSeller" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <form action="" method="post" autocomplete="off" class="card">
                            <div class="modal-header">
                                <h2 class="h6 modal-title">Edit Seller</h2>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="">Seller</label>
                                            <select class="form-select" name="" id="">
                                                <option value="" selected>Select Seller</option>
                                                <option value="1">Ali Abdullah</option>
                                                <option value="2">Rao Aleem</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="">Amount</label>
                                            <input type="number" name="" id="" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="">Payment Period</label>
                                            <input type="text" name="" id="" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label for="">Kanal</label>
                                            <input type="text" name="" id="" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label for="">Marla</label>
                                            <input type="text" name="" id="" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label for="">Feet</label>
                                            <input type="text" name="" id="" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link text-gray-600 ms-auto"
                                    data-bs-dismiss="modal">Close
                                </button>
                                <button type="submit" class="btn btn-success text-white fw-bolder">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addInvestor" tabindex="-1" aria-labelledby="addInvestor" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <form action="comp/project.investor.add.php" enctype="multipart/form-data" method="post"
                            autocomplete="off" class="card" id="addInvestor">
                            <div class="modal-header">
                                <h2 class="h6 modal-title">Add Investor</h2>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <?php if (!empty($insert_investors)) { ?>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="addInvestor">Investor</label>
                                            <select class="form-select" name="investor" id="addInvestor" required>
                                                <option value="" selected>Select Investor</option>
                                                <?php foreach ($insert_investors as $key => $investor) { 
                                                    ?>
                                                <option value="<?=$investor['acc_id']?>">
                                                    <?=$investor['name'].", ".cnic_format($investor['cnic'])?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="addRatio">Partnership Ratio (%)</label>
                                            <input type="number" name="ratio" id="addRatio" class="form-control"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label for="addKanal">Kanal</label>
                                            <input type="text" name="kanal" id="addKanal" class="form-control"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label for="addMarla">Marla</label>
                                            <input type="text" name="marla" id="addMarla" class="form-control"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label for="addFeet">Feet</label>
                                            <input type="text" name="feet" id="addFeet" class="form-control" required />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label class="btn btn-outline-primary w-100" for="investorDocs">Upload
                                                Document</label>
                                            <input class="form-control" type="file" name="docs[]" id="investorDocs"
                                                multiple hidden />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row d-none" id="investorFileUploadProgress">
                                            <div class="col-3">
                                                <button class="btn btn-primary w-100" id="investorPercent" disabled>
                                                    0%
                                                </button>
                                            </div>
                                            <div class="col-3">
                                                <button class="btn btn-primary w-100" id="investorDataTransferred"
                                                    disabled>
                                                    Total / Loaded
                                                </button>
                                            </div>
                                            <div class="col-3">
                                                <button class="btn btn-primary w-100" id="investorMbps" disabled>
                                                    0 Mbps
                                                </button>
                                            </div>
                                            <div class="col-3">
                                                <button class="btn btn-primary w-100" id="investorTimeLeft" disabled>
                                                    Time Left
                                                </button>
                                            </div>
                                            <div class="col-12 pt-3">
                                                <div class="progress-wrapper">
                                                    <div class="progress progress-xl">
                                                        <div class="progress-bar investor-progress-bar bg-primary"
                                                            role="progressbar" style="width: 0%;" aria-valuenow="25"
                                                            aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 pt-2">
                                                <button class="btn btn-primary w-100" id="investorCancel" disabled>
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-none" id="investorFilesTable">
                                            <table class="table table-centered table-nowrap mb-0 rounded">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="border-0 rounded-start">#</th>
                                                        <th class="border-0 rounded-end text-end">Doc Name
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-bolder">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php } else { ?>
                                    <h4 class="text-center">No Investor Available ...</h4>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link text-gray-600 ms-auto"
                                    data-bs-dismiss="modal">Close
                                </button>
                                <?php if (!empty($insert_investors)) { ?>
                                <button type="submit" class="btn btn-success text-white">Add Investor</button>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editInvestor" tabindex="-1" aria-labelledby="editInvestor" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <form action="" method="post" autocomplete="off" class="card">
                            <div class="modal-header">
                                <h2 class="h6 modal-title">Edit Investor</h2>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="">Investor</label>
                                            <select class="form-select" name="" id="">
                                                <option value="" selected>Select Investor</option>
                                                <option value="1">Ali Abdullah</option>
                                                <option value="2">Rao Aleem</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="">Partnership Ratio</label>
                                            <input type="number" name="" id="" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label for="">Kanal</label>
                                            <input type="text" name="" id="" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label for="">Marla</label>
                                            <input type="text" name="" id="" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label for="">Feet</label>
                                            <input type="text" name="" id="" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="">Select Document</label>
                                            <input class="form-control" type="file" name="" id="" multiple />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link text-gray-600 ms-auto"
                                    data-bs-dismiss="modal">Close
                                </button>
                                <button type="submit" class="btn btn-success text-white">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include('temp/footer.temp.php'); ?>
    </main>
    <?php include('temp/script.temp.php'); ?>
    <script>
    <?php if (isset($_GET['message'])) { ?>
    <?php if ($_GET['message'] == 'edit_true') { ?>
    notify("success", "Project Settings Updated ...");
    <?php } elseif ($_GET['message'] == 'edit_false') { ?>
    notify("error", "Something's Wrong, Report Error ...");
    <?php } ?>
    <?php } ?>
    var optionsPieChart = {
        series: [<?=$project['commercial_sqft']?>, <?=$project['residential_sqft']?>,
            <?=((($total_area_investors['kanal'] * 20) * 272.25) + ($total_area_investors['marla'] * 272.25) + $total_area_investors['feet'])-($project['commercial_sqft'] + $project['residential_sqft'])?>
        ],
        chart: {
            type: 'pie',
            height: 360,
        },
        theme: {
            monochrome: {
                enabled: true,
                color: '#198754',
            }
        },
        labels: ['Commercial', 'Residential', 'Wastage'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }],
        tooltip: {
            fillSeriesColor: false,
            onDatasetHover: {
                highlightDataSeries: false,
            },
            theme: 'light',
            style: {
                fontSize: '12px',
                fontFamily: 'Montserrat',
            },
            y: {
                formatter: function(val) {
                    return val.toLocaleString() + " SqFt."
                }
            }
        }
    };

    var pieChartEl = document.getElementById('totalSqft');
    if (pieChartEl) {
        var pieChart = new ApexCharts(pieChartEl, optionsPieChart);
        pieChart.render();
    }

    var optionsPieChart = {
        series: [<?php 
        if (!empty($area_investors)) {
                foreach ($area_investors as $key => $area_investor) {
                    echo $area_investor['total_sqft'];
                    if ($key != (count($area_investors) - 1)) {
                        echo ", ";
                    }
                }
            }
            ?>],
        chart: {
            type: 'pie',
            height: 360,
        },
        theme: {
            monochrome: {
                enabled: true,
                color: '#DC3545',
            }
        },
        labels: [<?php 
        if (!empty($area_investors)) {
                foreach ($area_investors as $key => $area_investor) {
                    foreach ($view_investors as $investor) {
                        if ($area_investor['acc_id'] == $investor['acc_id']) {
                            echo "'".$investor['name']."'";
                        }
                    }
                    if ($key != (count($area_investors) - 1)) {
                        echo ", ";
                    }
                }
            }
            ?>],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }],
        tooltip: {
            fillSeriesColor: false,
            onDatasetHover: {
                highlightDataSeries: false,
            },
            theme: 'light',
            style: {
                fontSize: '12px',
                fontFamily: 'Montserrat',
            },
            y: {
                formatter: function(val) {
                    return val.toLocaleString() + " SqFt."
                }
            }
        }
    };

    var pieChartEl = document.getElementById('investorSqft');
    if (pieChartEl) {
        var pieChart = new ApexCharts(pieChartEl, optionsPieChart);
        pieChart.render();
    }

    var optionsPieChart = {
        series: [180000000, 180000000, 180000000, 180000000],
        chart: {
            type: 'pie',
            height: 360,
        },
        theme: {
            monochrome: {
                enabled: true,
                color: '#1F2937',
            }
        },
        labels: ['Ali Abdullah', 'Ali Abdullah', 'Ali Abdullah', 'Ali Abdullah'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }],
        tooltip: {
            fillSeriesColor: false,
            onDatasetHover: {
                highlightDataSeries: false,
            },
            theme: 'light',
            style: {
                fontSize: '12px',
                fontFamily: 'Montserrat',
            },
            y: {
                formatter: function(val) {
                    return "Rs. " + val.toLocaleString()
                }
            }
        }
    };

    var pieChartEl = document.getElementById('sellerAmounts');
    if (pieChartEl) {
        var pieChart = new ApexCharts(pieChartEl, optionsPieChart);
        pieChart.render();
    }

    var optionsPieChart = {
        series: [<?php if (!empty($area_investors)) {
                foreach ($area_investors as $key => $area_investor) {
                    echo $area_investor['ratio'];
                    if ($key != (count($area_investors) - 1)) {
                        echo ", ";
                    }
                }
                if ($total_area_investors['ratio'] != 100) {
                    echo ", ".(100-$total_area_investors['ratio']);
                }
            }
            ?>],
        chart: {
            type: 'pie',
            height: 360,
        },
        theme: {
            monochrome: {
                enabled: true,
                color: '#1F2937',
            }
        },
        labels: [<?php if (!empty($area_investors)) {
                foreach ($area_investors as $key => $area_investor) {
                    foreach ($view_investors as $investor) {
                        if ($area_investor['acc_id'] == $investor['acc_id']) {
                            echo "'".$investor['name']."'";
                        }
                    }
                    if ($key != (count($area_investors) - 1)) {
                        echo ", ";
                    }
                }
            }
                if ($total_area_investors['ratio'] != 100) {
                    echo ", 'Remaining'";
                }
            ?>],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }],
        tooltip: {
            fillSeriesColor: false,
            onDatasetHover: {
                highlightDataSeries: false,
            },
            theme: 'light',
            style: {
                fontSize: '12px',
                fontFamily: 'Montserrat',
            },
            y: {
                formatter: function(val) {
                    return val + "%"
                }
            }
        }
    };

    var pieChartEl = document.getElementById('investorRatio');
    if (pieChartEl) {
        var pieChart = new ApexCharts(pieChartEl, optionsPieChart);
        pieChart.render();
    }

    <?php if (!empty($insert_investors)) { ?>
    $("#ratio").on("input", function() {
        if ($(this).val() > (100 - <?=$total_area_investors['ratio']?>)) {
            notify("error", "Ratio Must be in between <?=(100-$total_area_investors['ratio'])?>%");
            $(this).val("");
        }
    });
    <?php } ?>

    $(function() {
        <?php if (!empty($insert_investors)) { ?>
        var fileUpload = $("#investorDocs");

        fileUpload.change(function() {
            var all_file_names = "";
            var files = this.files;
            var errorMessages = [];
            var validFiles = [];
            var maxSize = 10000000; // in bytes

            var validFilesArray = Array.from(files).filter(file => {
                var fileName = file.name;
                var fileExtension = fileName.split('.').pop().toLowerCase();
                var fileSize = file.size;

                if (fileSize > maxSize) {
                    // Handle file size exceeding threshold
                    errorMessages.push("File size of '" + fileName + "' is larger than 10 MB.");
                    return false; // Exclude the file from the valid files array
                }

                if (fileExtension !== 'pdf' && fileExtension !== 'jpg' && fileExtension !==
                    'jpeg' &&
                    fileExtension !== 'png') {
                    // Handle invalid extensions
                    errorMessages.push("Invalid extension for file '" + fileName +
                        "'. Use only PDF, JPG, JPEG, or PNG.");
                    return false; // Exclude the file from the valid files array
                }

                return true; // File matches all conditions and is valid
            });

            var validFilesList = new DataTransfer();
            validFilesArray.forEach((file) => {
                validFilesList.items.add(file);
                $("#investorFileUploadProgress").removeClass('d-none');
                uploadFile(file);
                all_file_names += file['name'] + "-<?=$_SESSION['project']?>,";

            });
            var fileInput = $('#investorDocs')[0]; // Get the DOM element from jQuery object
            fileInput.files = validFilesList.files;

            updateSelectedFilesTable(validFilesArray);

            errorMessages.forEach(errorMessage => {
                notify("error", errorMessage);
            });
        });

        function updateSelectedFilesTable(validFiles) {
            var table = $('#investorFilesTable');
            var tableBody = $('#investorFilesTable tbody');

            // console.log(validFiles);
            table.removeClass('d-none').addClass('d-block');
            tableBody.empty();

            for (var i = 0; i < validFiles.length; i++) {
                var fileNameWithExt = validFiles[i]['name'];
                var fileNameWithoutExt = fileNameWithExt.split('.').slice(0, -1).join('.');
                var fileExt = fileNameWithExt.split('.').pop();

                tableBody.append('<tr><td>' + parseInt(i + 1) +
                    '</td><td class="text-end"><input class="border-0 text-end" type="text" name="file_names[]" value="' +
                    fileNameWithoutExt +
                    '" />.' + fileExt + '</td></tr>');
            }
            $("#investorFileUploadProgress").addClass('d-none');
        }

        function uploadFile(file) {
            var formData = new FormData();
            formData.append('file', file);

            var xhr = new XMLHttpRequest();

            xhr.upload.addEventListener("progress", function(e) {
                if (e.lengthComputable) {
                    var percentComplete = ((e.loaded / e.total) * 100);

                    // e.loaded is in bytes, convert it to kb, mb, or gb
                    var mbTotal = Math.floor(e.total / (1024));
                    var mbLoaded = Math.floor(e.loaded / (1024));

                    // calculate data transfer per sec
                    var time = (new Date().getTime() - startTime) / 1000;
                    var bps = e.loaded / time;
                    var Mbps = Math.floor(bps / (1024 * 1024));

                    // calculate remaining time
                    var remTime = (e.total - e.loaded) / bps;
                    var seconds = Math.floor(remTime % 60);
                    var minutes = Math.floor(remTime / 60);

                    // give output
                    $('#investorDataTransferred').html(`${mbLoaded}/${mbTotal} KBs`);
                    $('#investorMbps').html(`${Mbps} Mbps`);
                    $('#investorTimeLeft').html(`${minutes}:${seconds}s`);
                    $("#investorPercent").html(Math.floor(percentComplete) + '%');
                    $(".investor-progress-bar").width(percentComplete + '%');

                    // cancel button only works when the file is uploading
                    if (percentComplete > 0 && percentComplete < 100) {
                        $('#investorCancel').prop('disabled', false);
                    } else {
                        $('#investorCancel').prop('disabled', true);
                    }
                }
            }, false);

            var startTime = new Date().getTime();

            xhr.open('POST', 'ajax/docs.upload.php', true);
            xhr.send(formData);

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    if (xhr.responseText == "valid") {
                        notify("success", "Document '" + file['name'] + "' Uploaded ...");
                    } else if (xhr.responseText == "invalid") {
                        notify("error", "Document '" + file['name'] + "' not Uploaded ...");
                    }
                    // console.log('Response from server:', xhr.responseText);
                } else {
                    console.error('An error occurred:', xhr.statusText);
                }
            };

            xhr.onerror = function() {
                console.error('An error occurred during the request.');
            };

            // for cancel file transfer
            $('#investorCancel').on("click", () => {
                xhr.abort();
                $("#investorPercent").html('Canceled');
                $(".investor-progress-bar").width('0%');
            });
        }
        <?php } ?>
        <?php if (!empty($insert_sellers)) { ?>
        var fileUpload = $("#sellerDocs");

        fileUpload.change(function() {
            var all_file_names = "";
            var files = this.files;
            var errorMessages = [];
            var validFiles = [];
            var maxSize = 10000000; // in bytes

            var validFilesArray = Array.from(files).filter(file => {
                var fileName = file.name;
                var fileExtension = fileName.split('.').pop().toLowerCase();
                var fileSize = file.size;

                if (fileSize > maxSize) {
                    // Handle file size exceeding threshold
                    errorMessages.push("File size of '" + fileName + "' is larger than 10 MB.");
                    return false; // Exclude the file from the valid files array
                }

                if (fileExtension !== 'pdf' && fileExtension !== 'jpg' && fileExtension !==
                    'jpeg' &&
                    fileExtension !== 'png') {
                    // Handle invalid extensions
                    errorMessages.push("Invalid extension for file '" + fileName +
                        "'. Use only PDF, JPG, JPEG, or PNG.");
                    return false; // Exclude the file from the valid files array
                }

                return true; // File matches all conditions and is valid
            });

            var validFilesList = new DataTransfer();
            validFilesArray.forEach((file) => {
                validFilesList.items.add(file);
                $("#sellerFileUploadProgress").removeClass('d-none');
                uploadFile(file);
                all_file_names += file['name'] + "-<?=$_SESSION['project']?>,";

            });
            var fileInput = $('#sellerDocs')[0]; // Get the DOM element from jQuery object
            fileInput.files = validFilesList.files;

            updateSelectedFilesTable(validFilesArray);

            errorMessages.forEach(errorMessage => {
                notify("error", errorMessage);
            });
        });

        function updateSelectedFilesTable(validFiles) {
            var table = $('#sellerFilesTable');
            var tableBody = $('#sellerFilesTable tbody');

            // console.log(validFiles);
            table.removeClass('d-none').addClass('d-block');
            tableBody.empty();

            for (var i = 0; i < validFiles.length; i++) {
                var fileNameWithExt = validFiles[i]['name'];
                var fileNameWithoutExt = fileNameWithExt.split('.').slice(0, -1).join('.');
                var fileExt = fileNameWithExt.split('.').pop();

                tableBody.append('<tr><td>' + parseInt(i + 1) +
                    '</td><td class="text-end"><input class="border-0 text-end" type="text" name="file_names[]" value="' +
                    fileNameWithoutExt +
                    '" />.' + fileExt + '</td></tr>');
            }
            $("#sellerFileUploadProgress").addClass('d-none');
        }

        function uploadFile(file) {
            var formData = new FormData();
            formData.append('file', file);

            var xhr = new XMLHttpRequest();

            xhr.upload.addEventListener("progress", function(e) {
                if (e.lengthComputable) {
                    var percentComplete = ((e.loaded / e.total) * 100);

                    // e.loaded is in bytes, convert it to kb, mb, or gb
                    var mbTotal = Math.floor(e.total / (1024));
                    var mbLoaded = Math.floor(e.loaded / (1024));

                    // calculate data transfer per sec
                    var time = (new Date().getTime() - startTime) / 1000;
                    var bps = e.loaded / time;
                    var Mbps = Math.floor(bps / (1024 * 1024));

                    // calculate remaining time
                    var remTime = (e.total - e.loaded) / bps;
                    var seconds = Math.floor(remTime % 60);
                    var minutes = Math.floor(remTime / 60);

                    // give output
                    $('#sellerDataTransferred').html(`${mbLoaded}/${mbTotal} KBs`);
                    $('#sellerMbps').html(`${Mbps} Mbps`);
                    $('#sellerTimeLeft').html(`${minutes}:${seconds}s`);
                    $("#sellerPercent").html(Math.floor(percentComplete) + '%');
                    $(".seller-progress-bar").width(percentComplete + '%');

                    // cancel button only works when the file is uploading
                    if (percentComplete > 0 && percentComplete < 100) {
                        $('#sellerCancel').prop('disabled', false);
                    } else {
                        $('#sellerCancel').prop('disabled', true);
                    }
                }
            }, false);

            var startTime = new Date().getTime();

            xhr.open('POST', 'ajax/docs.upload.php', true);
            xhr.send(formData);

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    if (xhr.responseText == "valid") {
                        notify("success", "Document '" + file['name'] + "' Uploaded ...");
                    } else if (xhr.responseText == "invalid") {
                        notify("error", "Document '" + file['name'] + "' not Uploaded ...");
                    }
                    // console.log('Response from server:', xhr.responseText);
                } else {
                    console.error('An error occurred:', xhr.statusText);
                }
            };

            xhr.onerror = function() {
                console.error('An error occurred during the request.');
            };

            // for cancel file transfer
            $('#sellerCancel').on("click", () => {
                xhr.abort();
                $("#sellerPercent").html('Canceled');
                $(".seller-progress-bar").width('0%');
            });
        }
        <?php } ?>
    });
    </script>
</body>

</html>