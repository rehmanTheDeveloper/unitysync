<?php
session_start();
#################### Login & License Validation ####################
require("temp/validate.login.temp.php");                           #
$license_path = "license/".$_SESSION['license_username']."/license.json"; #
require("auth/license.validate.functions.php");                    #
require("temp/validate.license.temp.php");                         #
#################### Login & License Validation ####################

####################### Database Connection #######################
require("auth/config.php");                                       #
require("auth/functions.php");                                    #
$conn = conn("localhost", "root", "", "unitySync"); #
$DB_Connection = new DB("localhost", "unitySync", "root", ""); #
$PDO_conn = $DB_Connection->getConnection(); #
####################### Database Connection #######################

include("object/ledger.php");
$ledger_obj = new Ledger($PDO_conn);

$a = "";

$query = "SELECT * FROM `area_seller` WHERE `project_id` = '".$_SESSION['project']."';";
$area_sellers = fetch_Data($conn, $query);

$query = "SELECT `sqft_per_marla` FROM `project` WHERE `pro_id` = '".$_SESSION['project']."';";
$project_details = mysqli_fetch_assoc(mysqli_query($conn, $query));

$query = "SELECT * FROM `blocks` WHERE `project_id` = '".$_SESSION['project']."';";
$blocks = fetch_Data($conn, $query);

$query = "SELECT * FROM `properties` WHERE `project_id` = '".$_SESSION['project']."' ORDER BY CAST(SUBSTRING_INDEX(`pty_id`, '-', -1) AS UNSIGNED) DESC;";
$all_properties = fetch_Data($conn, $query);

if (!empty($all_properties)) {
    foreach ($all_properties as $key => $property) {
        $query = "SELECT * FROM `".$property['type']."` WHERE `pty_id` = '".$property['pty_id']."' AND `project_id` = '".$_SESSION['project']."';";
        $properties[$key] = mysqli_fetch_assoc(mysqli_query($conn, $query));
        $properties[$key]['type'] = $property['type'];
        $properties[$key]['delete'] = 1;
        $properties[$key]['status'] = 0;
        // TODO: Validate any of property type plot or flat
        $query = "SELECT `price`,`acc_id`,`sale_id` FROM `sale_installment` WHERE `pty_id` = '".$property['pty_id']."' AND `project_id` = '".$_SESSION['project']."';";
        $sale_installment = mysqli_query($conn, $query);
        if (mysqli_num_rows($sale_installment) > 0) {
            $properties[$key]['delete'] = 0;
            $sale_installment = mysqli_fetch_assoc($sale_installment);

            $sale_installment['debit'] = 0;
            $sale_installment['credit'] = 0;
            $fetch_ledger = [
                'type' => "sale",
                'sale_id' => $sale_installment['sale_id'],
                'source' => $sale_installment['acc_id'],
                'pay_to' => $sale_installment['acc_id'],
                'project' => $_SESSION['project']
            ];
            // $properties[$key]['ledger'] = $fetch_ledger;
            $sale_ledger = $ledger_obj->fetch($fetch_ledger);
            foreach ($sale_ledger as $single_ledger) {
                $sale_installment['debit'] += $single_ledger['debit'];
                $sale_installment['credit'] += $single_ledger['credit'];
            }
            if (($sale_installment['debit'] - $sale_installment['credit']) != 0) {
                $properties[$key]['status'] = 2;
            } elseif (($sale_installment['debit'] - $sale_installment['credit']) == 0) {
                $properties[$key]['status'] = 1;
            }
        }
        $query = "SELECT `price`,`acc_id`,`sale_id` FROM `sale_net_cash` WHERE `pty_id` = '".$property['pty_id']."' AND `project_id` = '".$_SESSION['project']."';";
        $sale_net_cash = mysqli_query($conn, $query);
        if (mysqli_num_rows($sale_net_cash) > 0) {
            $properties[$key]['delete'] = 0;
            $sale_net_cash = mysqli_fetch_assoc($sale_net_cash);
            
            $sale_net_cash['debit'] = 0;
            $sale_net_cash['credit'] = $sale_net_cash['price'];
            $fetch_ledger = [
                'type' => "sale",
                'sale_id' => $sale_net_cash['sale_id'],
                'source' => $sale_net_cash['acc_id'],
                'pay_to' => $sale_net_cash['acc_id'],
                'project' => $_SESSION['project']
            ];
            // $properties[$key]['ledger'] = $fetch_ledger;
            $sale_ledger = $ledger_obj->fetch($fetch_ledger);
            foreach ($sale_ledger as $single_ledger) {
                $sale_net_cash['debit'] += $single_ledger['debit'];
                $sale_net_cash['credit'] += $single_ledger['credit'];
            }
            if (($sale_net_cash['debit'] - $sale_net_cash['credit']) != 0) {
                $properties[$key]['status'] = 2;
            } elseif (($sale_net_cash['debit'] - $sale_net_cash['credit']) == 0) {
                $properties[$key]['status'] = 1;
            }
        }
    }
} else {
    $properties = [];
}

// echo "<pre>";
// print_r($properties);
// exit();

$title = "All Properties";
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
                        <a href="#">Master Entry</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Property
                    </li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between w-100 flex-wrap align-items-center">
                <h1 class="h4 mb-0">All Property</h1>
                <?php if (!empty($area_sellers)) { ?>
                <div class="btn-group">
                    <a href="property.config.php" class="btn btn-outline-gray-800 d-inline-flex align-items-center">
                        <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Property
                    </a>
                </div>
                <?php } ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="table-responsive py-4">
                        <table class="table table-centered table-nowrap mb-4 rounded table-hover" id="datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0">#</th>
                                    <th class="border-0 text-center">No.</th>
                                    <th class="border-0 text-center">Block</th>
                                    <th class="border-0 text-center">Plot Dimension</th>
                                    <th class="border-0 text-center">Area (Marla - Ft)</th>
                                    <th class="border-0 text-center">Category</th>
                                    <th class="border-0 text-center">Status</th>
                                    <th class="border-0 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($properties)) {
                                foreach ($properties as $key => $property) { ?>
                                <tr>
                                    <td class="fw-bolder">
                                        <?=$key+1?>
                                    </td>
                                    <td class="text-center text-capitalize">
                                        <?=$property['type']." #".$property['number']?></td>
                                    <td class="text-center">
                                        <?php foreach ($blocks as $block) {
                                            if ($property['block'] == $block['id']) {
                                                echo $block['name']."-".$block['street'];
                                            }
                                        } ?>
                                    </td>
                                    <td class="text-center"><?=$property['length']?> X <?=$property['width']?></td>
                                    <td class="text-center">
                                        <?=floor($property['sqft'] / $project_details['sqft_per_marla'])?> marla -
                                        <?=number_format(($property['sqft'] - floor($property['sqft'] / $project_details['sqft_per_marla']) * $project_details['sqft_per_marla']))?>
                                        Sqft.</td>
                                    <td class="text-capitalize text-center fw-bold"><?=$property['category']?></td>
                                    <td class="text-capitalize text-center fw-bold">
                                        <?php if ($property['status'] == 0) { ?>
                                        <span class="badge bg-danger">Unsold</span>
                                        <?php } elseif ($property['status'] == 1) { ?>
                                        <span class="badge bg-success">Sold</span>
                                        <?php } elseif ($property['status'] == 2) { ?>
                                        <span class="badge bg-warning">Under Financing</span>
                                        <?php } ?>
                                    </td>
                                    <td style="column-gap: 15px;" class="d-flex justify-content-end align-items-center">
                                        <a class="btn p-0" href="property.view.php?i=<?=encryptor("encrypt", $property['pty_id'])?>">
                                            <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                <path fill-rule="evenodd"
                                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </a>
                                        <div class="btn-group">
                                            <button class="btn btn-link dropdown-toggle dropdown-toggle-split m-0 p-0"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                title="Delete">
                                                <svg class="icon icon-xs <?=($property['delete'] == 1)?"text-danger":"text-gray-400"?>" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1"
                                                style="">
                                                <?php if ($property['delete'] == 1) { ?>
                                                <a class="dropdown-item d-flex align-items-center"
                                                    href="comp/property.delete.php?i=<?=encryptor("encrypt", $property['pty_id'])?>">
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

                                                    No
                                                </a>
                                                <?php } else { ?>
                                                <a class="dropdown-item d-flex align-items-center text-gray-700"
                                                    data-bs-dismiss="dropdown">
                                                    <svg class="icon icon-xs dropdown-icon text-gray-400 me-2"
                                                        fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Not Deletable ...
                                                </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php } } else { ?>
                                <tr>
                                    <td class="text-center fw-bold" colspan="8">No Property Created ...</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- <div class="col-4 d-flex align-items-center flex-column justify-content-center">
                <h2 class="mb-3 text-center">Total Property</h2>
                <div class="w-100" id="totalProperty"></div>
            </div> -->
        </div>

        <?php include('temp/footer.temp.php'); ?>
    </main>
    <?php include('temp/script.temp.php'); ?>

    <script>
    <?php if (isset($_GET['m'])) { ?>
    <?php if ($_GET['m'] == 'add_true') { ?>
    notify("success", "Property Created Successfully ...");
    <?php } elseif ($_GET['m'] == 'add_false') { ?>
    notify("error", "Something's Wrong, Report Error ...");
    <?php } elseif ($_GET['m'] == 'delete_true') { ?>
    notify("success", "Property Deleted Successfully ...");
    <?php } elseif ($_GET['m'] == 'delete_false') { ?>
    notify("error", "Something's Wrong, Report Error ...");
    <?php } ?>
    <?php } ?>
    // var optionsPieChart = {
    //     series: [44, 55, 29],
    //     chart: {
    //         type: 'pie',
    //         height: 360,
    //     },
    //     theme: {
    //         monochrome: {
    //             enabled: true,
    //             color: '#1F2937',
    //         }
    //     },
    //     labels: ['Plots', 'Flats', 'Files'],
    //     responsive: [{
    //         breakpoint: 480,
    //         options: {
    //             chart: {
    //                 width: 200
    //             },
    //             legend: {
    //                 position: 'bottom'
    //             }
    //         }
    //     }],
    //     tooltip: {
    //         fillSeriesColor: false,
    //         onDatasetHover: {
    //             highlightDataSeries: false,
    //         },
    //         theme: 'light',
    //         style: {
    //             fontSize: '12px',
    //             fontFamily: 'Montserrat',
    //         },
    //         y: {
    //             formatter: function(val) {
    //                 return val
    //             }
    //         }
    //     }
    // };

    // var pieChartEl = document.getElementById('totalProperty');
    // if (pieChartEl) {
    //     var pieChart = new ApexCharts(pieChartEl, optionsPieChart);
    //     pieChart.render();
    // }
    </script>
</body>

</html>