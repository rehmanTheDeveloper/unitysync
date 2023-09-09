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
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label class="form-label" for="propertyType">Property Type</label>
                                    <select class="form-select" name="type" id="propertyType">
                                        <option value="plot">Plot</option>
                                        <option value="flat">Flat</option>
                                        <option value="file">File</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 typePlot d-none">
                                <div class="mb-2">
                                    <label class="form-label" for="plotNo">Plot No</label>
                                    <input class="form-control" type="number" name="plotNo" id="plotNo"
                                        placeholder="e.g: 78,etc." />
                                </div>
                            </div>
                            <div class="col-md-4 typePlot d-none">
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
                                        <select class="form-select" name="plotBlock">
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
                                        <input type="text" name="plotBlockName" id="plotBlockName" class="form-control"
                                            placeholder="Block" />
                                        <span class="input-group-text bg-gray-100">-</span>
                                        <input type="text" name="plotStreet" id="plotStreet"
                                            class="form-control text-end" placeholder="Street" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 typePlot d-none">
                                <div class="mb-2">
                                    <label class="form-label d-flex justify-content-between" for="plotCategory">
                                        Category
                                        <span
                                            class="category_field"><?=number_format($project_details['residential_sqft'])." Sqft. / ".number_format(($project_details['residential_sqft'] / $project_details['sqft_per_marla']),1)." Marlas"?></span>
                                    </label>
                                    <select class="form-select category" name="plotCategory" id="plotCategory">
                                        <option value="residential">Residential</option>
                                        <option value="commercial">Commercial</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 typePlot d-none">
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <label for="plotLength">Length</label>
                                        <svg class="icon icon-xs me-2" data-bs-toggle="tooltip"
                                            data-bs-original-title="Dimension like 43 X 23, etc." fill="currentColor"
                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <label for="plotWidth">Width</label>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" name="plotLength" id="plotLength" class="form-control"
                                            placeholder="99, etc." />
                                        <span class="input-group-text bg-gray-100 text-primary">X</span>
                                        <input type="text" name="plotWidth" id="plotWidth" class="form-control text-end"
                                            placeholder="99, etc." />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 typePlot d-none">
                                <div class="mb-2">
                                    <label class="form-label" for="areaTypePlot">Area Type</label>
                                    <select class="form-select" name="areaTypePlot" id="areaTypePlot">
                                        <option value="kanal" selected>Kanal</option>
                                        <option value="marla">Marla</option>
                                        <option value="sqFt">Square Feet</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 typePlot d-none">
                                <div class="mb-2">
                                    <label class="form-label" for="plotArea">Plot Area</label>
                                    <input class="form-control" type="text" name="plotArea" id="plotArea"
                                        placeholder="e.g: 45,etc." />
                                </div>
                            </div>
                            <div class="col-md-3 typePlot d-none">
                                <div class="mb-2">
                                    <label class="form-label" for="plotMarla">Marla</label>
                                    <input class="form-control bg-white" type="text" name="plotMarla" id="plotMarla"
                                        readonly />
                                </div>
                            </div>
                            <div class="col-md-4 typeFlat d-none">
                                <div class="mb-2">
                                    <label class="form-label" for="flatNo">Flat No</label>
                                    <input class="form-control" type="number" name="flatNo" id="flatNo"
                                        placeholder="e.g: 391,etc." />
                                </div>
                            </div>
                            <div class="col-md-4 typeFlat d-none">
                                <div class="mb-2">
                                    <label class="d-flex justify-content-between">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input mb-0" type="radio" name="blockMethod"
                                                value="selectBlock" id="selectBlock" checked="checked" />
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
                                                <svg class="icon icon-xs ms-2" data-bs-toggle="tooltip" tabindex="0"
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
                                        <select class="form-select" name="flatBlock">
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
                                        <input type="text" name="flatBlockName" class="form-control"
                                            placeholder="Block" />
                                        <span class="input-group-text bg-gray-100">-</span>
                                        <input type="text" name="flatStreet" class="form-control text-end"
                                            placeholder="Street" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 typeFlat d-none">
                                <div class="mb-2">
                                    <label class="form-label d-flex justify-content-between" for="flatCategory">
                                        Category
                                        <span
                                            class="category_field"><?=number_format($project_details['residential_sqft'])." Sqft. / ".number_format(($project_details['residential_sqft'] / $project_details['sqft_per_marla']),1)." Marlas"?></span>
                                    </label>
                                    <select class="form-select category" name="flatCategory" id="flatCategory">
                                        <option value="residential">Residential</option>
                                        <option value="commercial">Commercial</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 typeFlat d-none">
                                <div class="mb-2">
                                    <label class="form-label" for="flatDimension">Flat Dimension</label>
                                    <input class="form-control" type="text" name="flatDimension" id="flatDimension"
                                        placeholder="e.g: 12 X 24,etc." />
                                </div>
                            </div>
                            <div class="col-md-3 typeFlat d-none">
                                <div class="mb-2">
                                    <label class="form-label" for="areaTypeFlat">Area Type</label>
                                    <select class="form-select" name="areaTypeFlat" id="areaTypeFlat">
                                        <option value="marla">Marla</option>
                                        <option value="sqFt">Square Feet</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 typeFlat d-none">
                                <div class="mb-2">
                                    <label class="form-label" for="flatArea">Flat Area</label>
                                    <input class="form-control" type="text" name="flatArea" id="flatArea"
                                        placeholder="e.g: 7,etc." />
                                </div>
                            </div>
                            <div class="col-md-3 typeFlat d-none">
                                <div class="mb-2">
                                    <label class="form-label" for="flatMarla">Marla</label>
                                    <input class="form-control bg-white" type="text" name="flatMarla" id="flatMarla"
                                        readonly />
                                </div>
                            </div>
                            <div class="col-md-4 typeFile d-none">
                                <div class="mb-2">
                                    <label class="form-label" for="fileForArea">File For Area</label>
                                    <input class="form-control" type="number" name="fileForArea" id="fileForArea"
                                        placeholder="e.g: 5,etc. marla" />
                                </div>
                            </div>
                            <div class="col-md-4 typeFile d-none">
                                <div class="mb-2">
                                    <label class="form-label" for="fileCategory">Category</label>
                                    <select class="form-select" name="fileCategory" id="fileCategory">
                                        <option value="residential">Residential</option>
                                        <option value="commercial">Commercial</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 typeFile d-none">
                                <div class="mb-2">
                                    <label class="form-label" for="quantityFiles">Quantity of Files</label>
                                    <input class="form-control" type="number" name="quantityFiles" id="quantityFiles"
                                        placeholder="Enter files ..." />
                                </div>
                            </div>

                            <div class="col-12 d-none remarks">
                                <div class="mb-2">
                                    <label class="form-label" for="remarks">Remarks</label>
                                    <input class="form-control" type="text" name="remarks" id="remarks"
                                        placeholder="Enter Remarks ..." />
                                </div>
                            </div>
                            <div class="col-12 text-center submit d-none">
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
        console.log(blocks);
        $("#plotStreet").on("focusout", function() {
            if ($("#plotBlockName").val()) {
                blocks.forEach(block => {
                    if (block['name'] == $("#plotBlockName").val() && block['street'] == $(
                            "#plotStreet").val()) {
                        notify("error", "Block Already exist ..");
                        $("#plotBlockName").focus();
                        $("#plotBlockName").val("");
                        $("#plotStreet").val("");
                    }
                });
            }
        });
        $("#propertyType").trigger("change");

        $('input[name="blockMethod"]').change(function() {
            var selectedMethod = $(this).val();
            // Hide all sections
            $('.selectBlockSection').hide();
            $('.typeBlockSection').hide();

            // Show the selected section
            $('.' + selectedMethod + 'Section').show();
            $('.' + selectedMethod + 'Section input').val("");
        });

        $(".category").on("change", function() {
            if ($(this).val() == "residential") {
                $(".category_field").text(category_residential.toLocaleString() + " Sqft. / " + (
                        category_residential / <?=$project_details['sqft_per_marla']?>).toFixed(1) +
                    " Marlas");
                $("#plotMarla").val("");
                $("#plotArea").val("");
            } else if ($(this).val() == "commercial") {
                $(".category_field").text(category_commercial.toLocaleString() + " Sqft. / " + (
                        category_commercial / <?=$project_details['sqft_per_marla']?>).toFixed(1) +
                    " Marlas");
                $("#plotMarla").val("");
                $("#plotArea").val("");
            }
        });

        $("#plotArea").on("keyup", function() {
            if ($("#areaTypePlot").val() == "kanal") {
                $("#plotMarla").val($(this).val() * 20);
                if ($(".category").val() == "residential") {
                    if ((<?=$project_details['residential_sqft']?> - ($(this).val() * 20 *
                            <?=$project_details['sqft_per_marla']?>)) < 0) {
                        notify("error",
                            "Total Sqfts. are <?=number_format($project_details['residential_sqft'])?> Sqft."
                            );
                        $(this).val("");
                        $("#plotMarla").val("0");
                    }
                }
                if ($(".category").val() == "commercial") {
                    if ((<?=$project_details['commercial_sqft']?> - ($(this).val() * 20 *
                            <?=$project_details['sqft_per_marla']?>)) < 0) {
                        notify("error",
                            "Total Sqfts. are <?=number_format($project_details['commercial_sqft'])?> Sqft."
                            );
                        $(this).val("");
                        $("#plotMarla").val("0");
                    }
                }
            } else if ($("#areaTypePlot").val() == "marla") {
                $("#plotMarla").val($(this).val());
                if ($(".category").val() == "residential") {
                    if ((<?=$project_details['residential_sqft']?> - ($(this).val() *
                            <?=$project_details['sqft_per_marla']?>)) < 0) {
                        notify("error",
                            "Total Sqfts. are <?=number_format($project_details['residential_sqft'])?> Sqft."
                            );
                        $(this).val("");
                        $("#plotMarla").val("0");
                    }
                }
                if ($(".category").val() == "commercial") {
                    if ((<?=$project_details['commercial_sqft']?> - ($(this).val() *
                            <?=$project_details['sqft_per_marla']?>)) < 0) {
                        notify("error",
                            "Total Sqfts. are <?=number_format($project_details['commercial_sqft'])?> Sqft."
                            );
                        $(this).val("");
                        $("#plotMarla").val("0");
                    }
                }
            } else if ($("#areaTypePlot").val() == "sqFt") {
                $("#plotMarla").val(($(this).val() / <?=$project_details['sqft_per_marla']?>).toFixed(
                    2));
                if ($(".category").val() == "residential") {
                    if ((<?=$project_details['residential_sqft']?> - ($(this).val())) < 0) {
                        notify("error",
                            "Total Sqfts. are <?=number_format($project_details['residential_sqft'])?> Sqft."
                            );
                        $(this).val("");
                        $("#plotMarla").val("0");
                    }
                }
                if ($(".category").val() == "commercial") {
                    if ((<?=$project_details['commercial_sqft']?> - ($(this).val())) < 0) {
                        notify("error",
                            "Total Sqfts. are <?=number_format($project_details['commercial_sqft'])?> Sqft."
                            );
                        $(this).val("");
                        $("#plotMarla").val("0");
                    }
                }
            }
        });
    });

    $("#propertyType").on("change", function() {
        let type = $(this).val();
        // Add all the class names to an array
        let classArray = ["submit", "remarks", "typeFlat", "typeFile", "typePlot"];

        if (type != "") {
            // Remove "d-none" class from all elements in the classArray
            classArray.forEach(function(className) {
                $("." + className).removeClass("d-none");
            });

            // Based on the selected type, add "d-none" class to specific elements
            if (type == "plot") {
                $(".typeFlat").addClass("d-none");
                $(".typeFile").addClass("d-none");
            } else if (type == "flat") {
                $(".typePlot").addClass("d-none");
                $(".typeFile").addClass("d-none");
            } else if (type == "file") {
                $(".typePlot").addClass("d-none");
                $(".typeFlat").addClass("d-none");
            }
        } else {
            // Add "d-none" class to all elements in the classArray
            classArray.forEach(function(className) {
                $("." + className).addClass("d-none");
            });
        }
    });
    </script>
</body>

</html>