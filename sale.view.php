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

$title = "Sale - #00123";
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
                        <a href="#">Transaction</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Sale Property - Sale #00123
                    </li>
                </ol>
                <div class="btn-group">
                <a href="sale.print.php" class="btn btn-outline-gray-800">
                        Print Voucher
                    </a>
                    <a href="property.view.php" class="btn btn-outline-gray-800">
                        Property Details
                    </a>
                    <a href="account.view.php" class="btn btn-outline-gray-800">
                        Customer Details
                    </a>
                    <a href="#" class="btn btn-outline-gray-800">
                        Edit Sale
                    </a>
                    <a href="sale.all.php" class="btn btn-outline-gray-800">
                        All sales
                    </a>
                </div>
            </nav>
        </div>

        <div style="row-gap: 20px;" class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h2 class="text-center mb-3">Voucher ID - #00123</h2>
                            </div>
                            <div class="col-12">
                                <table class="table table-centered table-nowrap mb-0 rounded">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-0 rounded-start">Property</th>
                                            <th class="border-0 rounded-end text-end">Detail
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bolder">
                                        <tr>
                                            <td>Block</td>
                                            <td class="text-end">
                                                B
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Street</td>
                                            <td class="text-end">
                                                234
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Plot Dimension</td>
                                            <td class="text-end">
                                                20 x 50
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Area</td>
                                            <td class="text-end">
                                                5 Marla - 6 Ft.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Owner</td>
                                            <td class="text-end">
                                                Ali Abdullah
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Phone No.</td>
                                            <td class="text-end">
                                                +92 345 674333583
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Category</td>
                                            <td class="text-end">
                                                Residential
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Total Amount</td>
                                            <td class="text-end">
                                                Rs. 550,000
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Paid Amount</td>
                                            <td class="text-end text-success">
                                                Rs. 440,000
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Pending Amount</td>
                                            <td class="text-end text-danger">
                                                Rs. 110,000
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 d-flex align-items-center flex-column justify-content-center">
                <h2 class="mb-3 text-center">Sale Amount</h2>
                <div class="w-100" id="totalAmount"></div>
            </div>
        </div>

        <?php if(TRUE) { ?>
        <div class="card my-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-centered table-nowrap mb-0 rounded" id="datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0 rounded-start">#</th>
                                    <th class="border-0">Transfered From</th>
                                    <th class="border-0 text-end rounded-end">Transfered To</th>
                                </tr>
                            </thead>
                            <tbody class="fw-bold">
                                <tr>
                                    <td>1</td>
                                    <td class="text-start">
                                        <span class="fw-bolder">Abdul Rehman</span><br />
                                        <span>+92 306 436322262</span><br />
                                        <span>Block B - Street #243 - #23</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bolder">Ali Ahmad</span><br />
                                        <span>+92 306 436322262</span><br />
                                        <span>Block B - Street #243 - #23</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td class="text-start">
                                        <span class="fw-bolder">Ali Ahmad</span><br />
                                        <span>+92 306 436322262</span><br />
                                        <span>Block B - Street #243 - #23</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bolder">Ali Abdullah</span><br />
                                        <span>+92 306 436322262</span><br />
                                        <span>Block B - Street #243 - #23</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php include('temp/footer.temp.php'); ?>
    </main>
    <?php include('temp/script.temp.php'); ?>
    <script>
    $(function() {
        $("#back").on("click", function() {
            window.history.back();
        });
    });
    var optionsPieChart = {
        series: [440000, 110000],
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
        labels: ['Paid', 'Unpaid'],
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

    var pieChartEl = document.getElementById('totalAmount');
    if (pieChartEl) {
        var pieChart = new ApexCharts(pieChartEl, optionsPieChart);
        pieChart.render();
    }
    </script>
</body>

</html>