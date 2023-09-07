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
$conn = conn("localhost", "root", "", "unitySync");             #
####################### Database Connection #######################

if ($_SESSION['role'] !== 'super-admin') {
    header("Location: index.php?m=masti");
}

$cities = array(
"Karachi",
"Lahore",
"Faisalabad",
"Rawalpindi",
"Gujranwala",
"Peshawar",
"Multan",
"Hyderabad",
"Islamabad",
"Quetta",
"Bahawalpur",
"Sargodha",
"Sialkot",
"Sukkur",
"LarkAdd Accountana",
"Rahim Yar Khan",
"Sheikhupura",
"Jhang",
"Talagang",
"Dera Ghazi Khan",
"Gujrat",
"Sahiwal",
"Wah Cantonment",
"Mardan",
"Kasur",
"Okara",
"Mingora",
"Nawabshah",
"Chiniot",
"Kotri",
"Kāmoke",
"Hafizabad",
"Sadiqabad",
"Mirpur Khas",
"Burewala",
"Kohat",
"Khanewal",
"Dera Ismail Khan",
"Turbat",
"Muzaffargarh",
"Abbottabad",
"Mandi Bahauddin",
"Shikarpur",
"Jacobabad",
"Jhelum",
"Khanpur",
"Khairpur",
"Khuzdar",
"Pakpattan",
"Hub",
"Daska",
"Gojra",
"Dadu",
"Muridke",
"Bahawalnagar",
"Samundri",
"Tando Allahyar",
"Tando Adam",
"Jaranwala",
"Chishtian",
"Muzaffarabad",
"Attock",
"Vehari",
"Kot Abdul Abdul",
"Ferozwala",
"Chakwal",
"Gujranwala Cantonment",
"Kamalia",
"Umerkot",
"Ahmedpur East",
"Kot Abdul Malik",
"Wazirabad",
"Mansehra",
"Layyah",
"Mirpur",
"Swabi",
"Chaman",
"Taxila",
"Nowshera",
"Khushab",
"Shahdadkot",
"Mianwali",
"Kabal",
"Lodhran",
"Hasilpur",
"Charsadda",
"Bhakkar",
"Badin",
"Arif",
"Ghotki",
"Sambrial",
"Jatoi",
"Haroonabad",
"Daharki",
"Narowal",
"Tando Muhammad Khan",
"Kamber Ali Khan",
"Mirpur Mathelo",
"Kandhkot",
"Bhalwal",
);

$countries = array(
    "pakistan"
);

$query = "SELECT * FROM `project` WHERE `pro_id` = '".$_SESSION['project']."';";
$project = mysqli_fetch_assoc(mysqli_query($conn, $query));

$query = "SELECT COALESCE(SUM(`kanal`), 0) as `kanal`, COALESCE(SUM(`marla`), 0) as `marla`, COALESCE(SUM(`feet`), 0) as `feet` FROM `area_seller` WHERE `project_id` = '".$_SESSION['project']."';";
$total_area_sellers = mysqli_fetch_assoc(mysqli_query($conn, $query));

$title = "Edit Project";
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
                        <a href="#">Configuration</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Project - <?=$project['name']?>
                    </li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between w-100 flex-wrap align-items-center">
                <h1 class="h4 mb-0">Edit Project - <?=$project['name']?></h1>
                <div class="btn-group">
                    <a id="back" class="btn btn-outline-gray-800">
                        Back
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="fw-bolder">General Information</h6>
                        <form method="POST" action="comp/project.edit.php" class="row">
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="name">Project Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="<?=$project['name']?>" required />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="project">Project Category <span
                                            class="text-danger">(<b>Unchangeable</b>)</span></label>
                                    <select class="form-select" name="project" id="project" disabled>
                                        <?php if ($project['category'] == "purchased") { ?>
                                        <option value="purchased" selected>Purchased</option>
                                        <?php } elseif ($project['category'] == "joint-venture") { ?>
                                        <option value="joint-venture" selected>Joint Venture</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-2">
                                    <label for="address">Address</label>
                                    <input type="text" name="address" id="address" class="form-control"
                                        value="<?=$project['address']?>" />
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-2">
                                    <label for="city">City</label>
                                    <select name="city" id="city" class="form-select" required>]
                                        <?php foreach ($cities as $city) { 
                                                if ($project['city'] == $city) {?>
                                        <option value="<?=$city?>" selected><?=$city?></option>
                                        <?php } else { ?>
                                        <option value="<?=$city?>"><?=$city?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-2">
                                    <label for="country">Country</label>
                                    <select name="country" id="country" class="form-select">
                                        <?php foreach ($countries as $key => $country) {
                                        if ($project['country'] == $country) { ?>
                                        <option value="<?=$country?>" selected><?=$country?></option>
                                        <?php } else { ?>
                                        <option value="<?=$country?>"><?=$country?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-2">
                                    <label for="phoneNo">Phone No.</label>
                                    <div class="input-group">
                                        <span class="input-group-text text-primary">+92</span>
                                        <input type="tel" name="phoneNo" id="phoneNo"
                                            class="form-control phone_no_format"
                                            value="<?=phone_no_format($project['phone_no'])?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-2">
                                    <label for="whtsNo">Whatsapp No.</label>
                                    <div class="input-group">
                                        <span class="input-group-text text-primary">+92</span>
                                        <input type="text" name="whtsNo" id="whtsNo"
                                            class="form-control phone_no_format"
                                            value="<?=phone_no_format($project['whatsapp_no'])?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-2">
                                    <label for="helplineNo">Helpline No.</label>
                                    <div class="input-group">
                                        <span class="input-group-text text-primary">+92</span>
                                        <input type="text" name="helplineNo" id="helplineNo"
                                            class="form-control phone_no_format"
                                            value="<?=phone_no_format($project['helpline_no'])?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-2">
                                    <label for="website">Website</label>
                                    <input type="text" name="website" id="website" class="form-control"
                                        value="<?=$project['website']?>" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="my-lg-3 mb-3">
                                    <label for="fbLink">Facebook Link</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-facebook icon icon-xs text-gray-600">
                                                <path
                                                    d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z">
                                                </path>
                                            </svg>
                                        </span>
                                        <input type="url" class="form-control" id="fbLink" name="fbLink"
                                            placeholder="www.facebook.com" value="<?=$project['fb_link']?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="my-lg-3 mb-3">
                                    <label for="ytLink">Youtube Link</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-youtube icon icon-xs text-gray-600">
                                                <path
                                                    d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z">
                                                </path>
                                                <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02">
                                                </polygon>
                                            </svg>
                                        </span>
                                        <input type="url" class="form-control" id="ytLink" name="ytLink"
                                            placeholder="www.youtube.com" value="<?=$project['yt_link']?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="my-lg-3 mb-3">
                                    <label for="itLink">Instagram Link</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-instagram icon icon-xs text-gray-600">
                                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z">
                                                </path>
                                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                            </svg>
                                        </span>
                                        <input type="url" class="form-control" id="itLink" name="itLink"
                                            placeholder="www.instagram.com" value="<?=$project['inst_link']?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="my-lg-3 mb-3">
                                    <label for="twLink">Twitter Link</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-twitter icon icon-xs text-gray-600">
                                                <path
                                                    d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z">
                                                </path>
                                            </svg>
                                        </span>
                                        <input type="url" class="form-control" id="twLink" name="twLink"
                                            placeholder="www.twitter.com" value="<?=$project['tw_link']?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-3 mt-2">
                                    <label for="total_land_sq_ft">Total Land Sqft.</label>
                                    <input type="text" id="total_land_sq_ft" class="form-control bg-white"
                                        value="<?=number_format((($total_area_sellers['kanal'] * 20) * 272.25) + ($total_area_sellers['marla'] * 272.25) + $total_area_sellers['feet'])?>"
                                        readonly />
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="mb-3 mt-2">
                                    <label for="commercial_sqft">Commercial Sqft.</label>
                                    <input type="number" name="commercial_sqft" id="commercial_sqft"
                                        class="form-control" value="<?=(empty($project['commercial_sqft']))?0:$project['commercial_sqft']?>" />
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="mb-3 mt-2">
                                    <label for="residential_sqft">Residential Sqft.</label>
                                    <input type="number" name="residential_sqft" id="residential_sqft"
                                        class="form-control" value="<?=(empty($project['residential_sqft']))?0:$project['residential_sqft']?>" />
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="mb-3 mt-2">
                                    <label for="wastage_sqft">Wastage Sqft.</label>
                                    <input type="text" name="wastage_sqft" id="wastage_sqft"
                                        class="form-control  bg-white"
                                        value="<?=(!empty($project['commercial_sqft']) && !empty($project['residential_sqft']))?number_format(((($total_area_sellers['kanal'] * 20) * 272.25) + ($total_area_sellers['marla'] * 272.25) + $total_area_sellers['feet'])-($project['commercial_sqft'] + $project['residential_sqft'])):"0"?>"
                                        readonly />
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-3 mt-2">
                                    <label for="sqft_per_marla">Sqft. per marla</label>
                                    <input type="number" name="sqft_per_marla" id="sqft_per_marla" class="form-control"
                                        value="<?=$project['sqft_per_marla']?>" />
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <input type="submit" value="Submit" class="btn btn-outline-gray-600" />
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
        $("#back").on("click", function() {
            window.history.back();
        });

        function wastage_sqft() {
            var wastage =
                <?=(($total_area_sellers['kanal'] * 20) * 272.25) + ($total_area_sellers['marla'] * 272.25) + $total_area_sellers['feet']?> -
                (parseInt($("#residential_sqft").val()) + parseInt($("#commercial_sqft").val()));
            return wastage;
        }

        function wastage_calculation(validate, output) {
            setTimeout(() => {
                if (!validate.val()) {
                    validate.val('0');
                }
                output.val(wastage_sqft());
            }, 500);
        }

        $("#residential_sqft").on("keyup keydown", () => {
            if ((<?=(($total_area_sellers['kanal'] * 20) * 272.25) + ($total_area_sellers['marla'] * 272.25) + $total_area_sellers['feet']?> -
                    (parseInt($("#residential_sqft").val()) + parseInt($("#commercial_sqft").val()))) <=
                0) {
                $("#residential_sqft").val("0");
                $("#commercial_sqft").val("0");
            }
            wastage_calculation($("#residential_sqft"), $("#wastage_sqft"));
        });

        $("#commercial_sqft").on("keyup keydown", () => {
            wastage_calculation($("#commercial_sqft"), $("#wastage_sqft"));
        });
    });
    </script>
</body>

</html>