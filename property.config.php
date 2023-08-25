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
                    <div class="card-body">
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
                                    <label class="form-label" for="plotBlock">Block</label>
                                    <div class="input-group">
                                        <?php if(!empty($blocks)): ?>
                                        <select class="form-select" name="plotBlock" id="plotBlock">
                                            <?php for ($i = 0; $i < count($blocks); $i++): ?>
                                            <option value="<?php echo $blocks[$i]['name']; ?>">
                                                <?php echo $blocks[$i]['name']; ?>
                                            </option>
                                            <?php endfor; ?>
                                        </select>
                                        <?php else: ?>
                                        <select class="form-select bg-white" disabled>
                                            <option value="" selected>No Block has been Added</option>
                                        </select>
                                        <?php endif; ?>
                                        <a class="input-group-text" data-bs-toggle="modal" data-bs-target="#addBlock">
                                            <svg data-bs-toggle="tooltip" data-bs-title="" class="icon icon-xs"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 typePlot d-none">
                                <div class="mb-2">
                                    <label class="form-label" for="plotCategory">Category</label>
                                    <select class="form-select" name="plotCategory" id="plotCategory">
                                        <option value="residential">Residential</option>
                                        <option value="commercial">Commercial</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 typePlot d-none">
                                <div class="mb-2">
                                    <label class="form-label" for="plotDimension">Plot Dimension</label>
                                    <input class="form-control" type="text" name="plotDimension" id="plotDimension"
                                        placeholder="e.g: 35 X 42,etc." />
                                </div>
                            </div>
                            <div class="col-md-3 typePlot d-none">
                                <div class="mb-2">
                                    <label class="form-label" for="areaTypePlot">Area Type</label>
                                    <select class="form-select" name="areaTypePlot" id="areaTypePlot">
                                        <option value="kanal">Kanal</option>
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
                                    <label class="form-label" for="flatBlock">Block</label>
                                    <div class="input-group">
                                        <?php if(!empty($blocks)): ?>
                                        <select class="form-select" name="flatBlock" id="flatBlock">
                                            <?php for ($i = 0; $i < count($blocks); $i++): ?>
                                            <option value="<?php echo $blocks[$i]['name']; ?>">
                                                <?php echo $blocks[$i]['name']; ?>
                                            </option>
                                            <?php endfor; ?>
                                        </select>
                                        <?php else: ?>
                                        <select class="form-select bg-white" disabled>
                                            <option value="" selected>No Block has been Added</option>
                                        </select>
                                        <?php endif; ?>
                                        <a class="input-group-text" data-bs-toggle="modal" data-bs-target="#addBlock">
                                            <i class="icon icon-xs" data-feather="plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 typeFlat d-none">
                                <div class="mb-2">
                                    <label class="form-label" for="flatCategory">Category</label>
                                    <select class="form-select" name="flatCategory" id="flatCategory">
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
    $(function() {
        $("#propertyType").trigger("change");
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