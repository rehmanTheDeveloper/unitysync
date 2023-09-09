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
$conn = conn("localhost", "root", "", "unitySync");                   #
####################### Database Connection #######################

// Validation for no property purchased
$query = "SELECT * FROM `area_seller` WHERE `project_id` = '".$_SESSION['project']."';";
$area_sellers = fetch_Data($conn, $query);
// Validation for no property purchased

if (empty($area_sellers)) {
    header("Location: property.all.php?m=not_found");
    exit();
}

$query = "SELECT * FROM `blocks` WHERE `project_id` = '".$_SESSION['project']."';";
$blocks = fetch_Data($conn, $query);

$query = "SELECT SUM(`sqft`) as `sqft` FROM `plot` WHERE `project_id` = '".$_SESSION['project']."';";
$plot_sqft = mysqli_fetch_assoc(mysqli_query($conn, $query));

$query = "SELECT `sqft_per_marla`,`residential_sqft`,`commercial_sqft` FROM `project` WHERE `pro_id` = '".$_SESSION['project']."';";
$project_details = mysqli_fetch_assoc(mysqli_query($conn, $query));

$project_details['residential_sqft'] = $project_details['residential_sqft'] - $plot_sqft['sqft'];
$project_details['commercial_sqft'] = $project_details['commercial_sqft'] - $plot_sqft['sqft'];

// echo "<pre>";
// print_r($project_details);
// exit();

$title = "Add Property";
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
                        Add Property
                    </li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between w-100 flex-wrap align-items-center">
                <h1 class="h4 mb-0">Add Property</h1>
                <div>
                    <a href="property.all.php" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
                        All Property
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form method="POST" action="comp/property.add.php" class="card-body">
                        <h6 class="fw-bolder">General Information</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label class="form-label" for="number">Plot/Flat No.</label>
                                    <input class="form-control" type="number" name="number" id="number"
                                        placeholder="e.g: 78,etc." required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label class="form-label" for="type">Property Type</label>
                                    <select class="form-select" name="type" id="type" required>
                                        <option value="plot">Plot</option>
                                        <option value="flat">Flat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label class="d-flex justify-content-between">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input mb-0" type="radio" name="blockMethod"
                                                value="selectBlock" id="selectBlock" checked />
                                            <label class="form-check-label mb-0" for="selectBlock">Select Block
                                                <svg class="icon icon-xs ms-2" data-bs-toggle="tooltip"
                                                    data-bs-original-title="" fill="currentColor" viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </label>
                                        </div>
                                        <div class="form-check mb-0">
                                            <input class="form-check-input mb-0" type="radio" name="blockMethod"
                                                id="typeBlock" value="typeBlock" />
                                            <label class="form-check-label mb-0" for="typeBlock">Add Block
                                                <svg class="icon icon-xs ms-2" data-bs-toggle="tooltip"
                                                    data-bs-original-title="Add Block which isn't defined."
                                                    fill="currentColor" viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </label>
                                        </div>
                                    </label>
                                    <div class="input-group selectBlockSection">
                                        <?php if(!empty($blocks)) { ?>
                                        <select class="form-select" name="block">
                                            <?php foreach ($blocks as $block){ ?>
                                            <option value="<?=$block['id']?>">
                                                <?=$block['name']."-".$block['street']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                        <?php } else { ?>
                                        <select class="form-select">
                                            <option value="" selected>No Block has been Added</option>
                                        </select>
                                        <?php } ?>
                                    </div>
                                    <div class="input-group typeBlockSection" style="display: none;">
                                        <input type="text" name="blockName" id="blockName" class="form-control"
                                            placeholder="Block" />
                                        <span class="input-group-text bg-gray-100">-</span>
                                        <input type="text" name="street" id="street" class="form-control text-end"
                                            placeholder="Street" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label class="form-label" for="category">
                                        Category
                                    </label>
                                    <select class="form-select category" name="category" id="category" required>
                                        <option value="residential">Residential</option>
                                        <option value="commercial">Commercial</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <label for="width">Width</label>
                                        <svg class="icon icon-xs me-2" data-bs-toggle="tooltip"
                                            data-bs-original-title="Dimension like 43 X 23, etc." fill="currentColor"
                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <label for="length">Length</label>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" name="width" id="width" class="form-control"
                                            placeholder="99, etc." required />
                                        <span class="input-group-text bg-gray-100 text-primary">X</span>
                                        <input type="text" name="length" id="length" class="form-control text-end"
                                            placeholder="99, etc." required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label class="form-label" for="rate">Price</label>
                                    <input class="form-control comma" type="text" name="rate" id="rate"
                                        placeholder="e.g: 7,800,000,etc." required />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-2">
                                    <label class="form-label" for="areaType">Area Type</label>
                                    <select class="form-select" name="areaType" id="areaType" required>
                                        <option value="kanal" selected>Kanal</option>
                                        <option value="marla">Marla</option>
                                        <option value="sqFt">Square Feet</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-2">
                                    <label class="form-label d-flex justify-content-between" for="area">
                                        <span>Area</span>
                                        <span
                                            class="category_field"><?=number_format(($project_details['residential_sqft'] / $project_details['sqft_per_marla']),2)." Marlas"?></span>
                                        <span>Marla</span>
                                    </label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="area" id="area"
                                            placeholder="e.g: 45,etc." required />
                                        <input class="form-control bg-white text-end" type="text" name="marla"
                                            id="marla" value="0" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="col-7">
                                <div class="mb-2">
                                    <label class="form-label" for="remarks">Remarks</label>
                                    <input class="form-control" type="text" name="remarks" id="remarks"
                                        placeholder="Enter Remarks ..." />
                                </div>
                            </div>
                            <div class="col-12 text-center submit">
                                <input class="btn btn-outline-gray-600 my-3" type="submit" name="submit"
                                    value="submit" />
                            </div>
                        </div>
                </div>
            </div>
        </div>
        </div>

        <?php include('temp/footer.temp.php'); ?>
    </main>
    <?php include('temp/script.temp.php'); ?>
    <script>
    <?php if (isset($_GET['m'])) { ?>
    <?php if ($_GET['m'] == 'add_false') { ?>
    notify("error", "Something's Wrong, Report Error ...");
    <?php } ?>
    <?php } ?>
    $(function() {
        var blocks = <?=json_encode($blocks)?>;
        var category_residential = <?=($project_details['residential_sqft'])?>;
        var category_commercial = <?=($project_details['commercial_sqft'])?>;
        $("#street").on("focusout", function() {
            if ($("#blockName").val()) {
                blocks.forEach(block => {
                    if (block['name'] == $("#blockName").val() && block['street'] == $(
                            "#street").val()) {
                        notify("error", "Block Already exist ..");
                        $("#blockName").focus();
                        $("#blockName").val("");
                        $("#street").val("");
                    }
                });
            }
        });

        $(".category").on("change", function() {
            if ($(this).val() == "residential") {
                $(".category_field").text((
                        category_residential / <?=$project_details['sqft_per_marla']?>).toFixed(1) +
                    " Marlas");
                $("#marla").val("0");
                $("#area").val("");
            } else if ($(this).val() == "commercial") {
                $(".category_field").text((
                        category_commercial / <?=$project_details['sqft_per_marla']?>).toFixed(1) +
                    " Marlas");
                $("#marla").val("0");
                $("#area").val("");
            }
        });

        $('input[name="blockMethod"]').change(function() {
            var selectedMethod = $(this).val();
            // Hide all sections
            $('.selectBlockSection').hide();
            $('.typeBlockSection').hide();

            // Show the selected section
            $('.' + selectedMethod + 'Section').show();
            $('.' + selectedMethod + 'Section input').val("");
        });

        $("#area").on("keyup", function() {
            if ($("#areaType").val() == "kanal") {
                $("#marla").val($(this).val() * 20);
                if ($(".category").val() == "residential") {
                    if ((<?=$project_details['residential_sqft']?> - ($(this).val() * 20 *
                            <?=$project_details['sqft_per_marla']?>)) < 0) {
                        notify("error",
                            "Limit is <?=number_format($project_details['residential_sqft'] / $project_details['sqft_per_marla'],2)?> Marlas."
                        );
                        $(this).addClass("is-invalid");
                        $("#marla").addClass("is-invalid");
                        $(this).val("");
                        $("#marla").val("0");
                    } else {
                        $(this).removeClass("is-invalid");
                        $("#marla").removeClass("is-invalid");
                    }
                }
                if ($(".category").val() == "commercial") {
                    if ((<?=$project_details['commercial_sqft']?> - ($(this).val() * 20 *
                            <?=$project_details['sqft_per_marla']?>)) < 0) {
                        notify("error",
                            "Limit is <?=number_format($project_details['commercial_sqft'] / $project_details['sqft_per_marla'],2)?> Marlas."
                        );
                        $(this).addClass("is-invalid");
                        $("#marla").addClass("is-invalid");
                        $(this).val("");
                        $("#marla").val("0");
                    } else {
                        $(this).removeClass("is-invalid");
                        $("#marla").removeClass("is-invalid");
                    }
                }
            } else if ($("#areaType").val() == "marla") {
                $("#marla").val($(this).val());
                if ($(".category").val() == "residential") {
                    if ((<?=$project_details['residential_sqft']?> - ($(this).val() *
                            <?=$project_details['sqft_per_marla']?>)) < 0) {
                        notify("error",
                            "Limit is <?=number_format($project_details['residential_sqft'] / $project_details['sqft_per_marla'],2)?> Marlas."
                        );
                        $(this).addClass("is-invalid");
                        $("#marla").addClass("is-invalid");
                        $(this).val("");
                        $("#marla").val("0");
                    } else {
                        $(this).removeClass("is-invalid");
                        $("#marla").removeClass("is-invalid");
                    }
                }
                if ($(".category").val() == "commercial") {
                    if ((<?=$project_details['commercial_sqft']?> - ($(this).val() *
                            <?=$project_details['sqft_per_marla']?>)) < 0) {
                        notify("error",
                            "Limit is <?=number_format($project_details['commercial_sqft'] / $project_details['sqft_per_marla'],2)?> Marlas."
                        );
                        $(this).addClass("is-invalid");
                        $("#marla").addClass("is-invalid");
                        $(this).val("");
                        $("#marla").val("0");
                    } else {
                        $(this).removeClass("is-invalid");
                        $("#marla").removeClass("is-invalid");
                    }
                }
            } else if ($("#areaType").val() == "sqFt") {
                $("#marla").val(($(this).val() /
                    <?=$project_details['sqft_per_marla']?>).toFixed(
                    2));
                if ($(".category").val() == "residential") {
                    if ((<?=$project_details['residential_sqft']?> - ($(this).val())) < 0) {
                        notify("error",
                            "Limit is <?=number_format($project_details['residential_sqft'] / $project_details['sqft_per_marla'],2)?> Marlas."
                        );
                        $(this).addClass("is-invalid");
                        $("#marla").addClass("is-invalid");
                        $(this).val("");
                        $("#marla").val("0");
                    } else {
                        $(this).removeClass("is-invalid");
                        $("#marla").removeClass("is-invalid");
                    }
                }
                if ($(".category").val() == "commercial") {
                    if ((<?=$project_details['commercial_sqft']?> - ($(this).val())) < 0) {
                        notify("error",
                            "Limit is <?=number_format($project_details['commercial_sqft'] / $project_details['sqft_per_marla'],2)?> Marlas."
                        );
                        $(this).addClass("is-invalid");
                        $("#marla").addClass("is-invalid");
                        $(this).val("");
                        $("#marla").val("0");
                    } else {
                        $(this).removeClass("is-invalid");
                        $("#marla").removeClass("is-invalid");
                    }
                }
            }
        });
    });
    </script>
</body>

</html>