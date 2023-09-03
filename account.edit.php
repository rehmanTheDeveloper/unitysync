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
$conn = conn("localhost", "root", "", "communiSync");                   #
####################### Database Connection #######################

if (empty($_GET['i'])) {
    header("Location: Accounts?m=not_found");
    exit();
}

$query = "SELECT `type` FROM `accounts` WHERE `acc_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';";
$type = mysqli_fetch_assoc(mysqli_query($conn, $query));

$query = "SELECT * FROM `".$type['type']."` WHERE `acc_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';";
$account = mysqli_fetch_assoc(mysqli_query($conn, $query));

if (empty($account)) {
    header("Location: Accounts?m=not_found");
    exit();
}

// echo "<pre>";
// print_r($account);
// exit();

$acc_types = [
    "customer", "seller", "investor", "staff", "expenses", "bank"
];
$prefixes = [
    "Mr.", "Mrs."
];
$cities = [
    "Karachi", "Lahore", "Faisalabad", "Rawalpindi", "Gujranwala", "Peshawar", "Multan", "Hyderabad", "Islamabad", "Quetta",
    "Bahawalpur", "Sargodha", "Sialkot", "Sukkur", "Larkana", "Rahim Yar Khan", "Sheikhupura", "Jhang", "Talagang",
    "Dera Ghazi Khan", "Gujrat", "Sahiwal", "Wah Cantonment", "Mardan", "Kasur", "Okara", "Mingora", "Nawabshah",
    "Chiniot", "Kotri", "Kāmoke", "Hafizabad", "Sadiqabad", "Mirpur Khas", "Burewala", "Kohat", "Khanewal", "Dera Ismail Khan",
    "Turbat", "Muzaffargarh", "Abbottabad", "Mandi Bahauddin", "Shikarpur", "Jacobabad", "Jhelum", "Khanpur", "Khairpur",
    "Khuzdar", "Pakpattan", "Hub", "Daska", "Gojra", "Dadu", "Muridke", "Bahawalnagar", "Samundri", "Tando Allahyar",
    "Tando Adam", "Jaranwala", "Chishtian", "Muzaffarabad", "Attock", "Vehari", "Kot Abdul Malik", "Ferozwala", "Chakwal",
    "Gujranwala Cantonment", "Kamalia", "Umerkot", "Ahmedpur East", "Kot", "Wazirabad", "Mansehra", "Layyah", "Mirpur",
    "Swabi", "Chaman", "Taxila", "Nowshera", "Khushab", "Shahdadkot", "Mianwali", "Kabal", "Lodhran", "Hasilpur",
    "Charsadda", "Bhakkar", "Badin", "Arif", "Ghotki", "Sambrial", "Jatoi", "Haroonabad", "Daharki", "Narowal",
    "Tando Muhammad Khan", "Kamber Ali Khan", "Mirpur Mathelo", "Kandhkot", "Bhalwal"
];
$provinces = [
    "punjab", "sindh", "balochistan", "khyber pakhtunkhwa"
];
$emails = [
    "@gmail.com", "@outlook.com", "@icloud.com",
];
$account['email'] = explode("@", $account['email']);
$account['email_ext'] = "@".$account['email'][1];

$title = "Edit Account";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('temp/head.temp.php'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
    <style type="text/css">
    .preview {
        overflow: hidden;
        width: 160px;
        height: 160px;
        margin: 10px;
        border: 1px solid red;
    }
    </style>
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
                        Edit Account
                    </li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between w-100 flex-wrap align-items-center">
                <h1 class="h4 mb-0"><?=$account['name']?></h1>
                <div>
                    <a id="back" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
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
                        <form method="POST" action="comp/account.edit.php" autocomplete="off" class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="mb-2">
                                    <label for="accTitle">Account Title</label>
                                    <input class="form-control" id="accTitle" name="accTitle" type="text"
                                        placeholder="Enter Account Title ..." value="<?=$account['name']?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="accGroup">Account Group</label>
                                    <select class="form-select" name="accGroup" id="accGroup">
                                        <?php foreach ($acc_types as $key => $acc_type) { 
                                            if ($acc_type == $type['type']) {?>
                                        <option value="<?=$acc_type?>"><?=$acc_type?></option>
                                        <?php } 
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 d-none" id="CusSellerSection">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <div class="col-lg-2">
                                                <div class="mb-2">
                                                    <label for="title">Title</label>
                                                    <select class="form-select" name="title" id="title">
                                                        <?php foreach ($prefixes as $key => $prefix) { 
                                                            if ($prefix == $account['prefix']) { ?>
                                                        <option value="<?=$prefix?>" selected><?=$prefix?></option>
                                                        <?php } else { ?>
                                                        <option value="<?=$prefix?>"><?=$prefix?></option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="mb-2">
                                                    <label for="fName">Father Name</label>
                                                    <input type="text" class="form-control" id="fName" name="fName"
                                                        aria-describedby="fatherName"
                                                        value="<?=$account['father_name']?>" />
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="mb-2">
                                                    <label for="cnicNum">CNIC Number</label>
                                                    <input class="form-control cnic_format" type="text" name="cnicNum"
                                                        id="cnicNum" aria-describedby="CNICnumber"
                                                        value="<?=cnic_format($account['cnic'])?>" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-2">
                                                    <label for="address">Address</label>
                                                    <input class="form-control" type="text" name="address" id="address"
                                                        value="<?=$account['address']?>" />
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-2">
                                                    <label for="city">City</label>
                                                    <select name="city" id="city" class="form-select" required>
                                                        <option value="" selected>Select City</option>
                                                        <?php foreach ($cities as $key => $city) { 
                                                            if ($city == $account['city']) {?>
                                                        <option value="<?=$city?>" selected><?=$city?></option>
                                                        <?php } else { ?>
                                                        <option value="<?=$city?>"><?=$city?></option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-2">
                                                    <label for="province">State</label>
                                                    <select class="form-select" name="province" id="province" required>
                                                        <option value="" selected>Select State</option>
                                                        <?php foreach ($provinces as $key => $province) { 
                                                            if ($province == $account['province']) {?>
                                                        <option value="<?=$province?>" selected><?=$province?></option>
                                                        <?php } else { ?>
                                                        <option value="<?=$province?>"><?=$province?></option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-2">
                                                    <label for="country">Country</label>
                                                    <select name="country" id="country" class="form-select" required>
                                                        <option value="">Select country</option>
                                                        <option value="pakistan" selected>Pakistan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 d-none cusDetail">
                                                <div class="mb-2">
                                                    <label for="nextKin">Next Kin</label>
                                                    <input class="form-control" type="text" name="nextKin" id="nextKin"
                                                        ariadescribedby="nextKin" />
                                                </div>
                                            </div>
                                            <div class="col-lg-4 d-none cusDetail">
                                                <div class="mb-2">
                                                    <label for="relationship">Relationship</label>
                                                    <select class="form-select" name="relationship" id="relationship">
                                                        <option value="" selected>Select relationship</option>
                                                        <option value="brother">Brother</option>
                                                        <option value="sister">Sister</option>
                                                        <option value="son">Son</option>
                                                        <option value="daughter">Daughter</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 d-none cusDetail">
                                                <div class="mb-2">
                                                    <label for="nextKinCNIC">Next Kin CNIC</label>
                                                    <input class="form-control cnic_format" type="text"
                                                        name="nextKinCNIC" id="nextKinCNIC"
                                                        ariadescribedby="nextKinCNIC" />
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-2">
                                                    <label for="phoneNo">Phone Number</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text text-primary">+92</span>
                                                        <input class="form-control phone_no_format" type="tel"
                                                            name="phoneNo" id="phoneNo" ariadescribedby="phoneNo"
                                                            value="<?=phone_no_format($account['phone_no'])?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-2">
                                                    <label for="email">E-mail</label>
                                                    <div class="input-group">
                                                        <input class="form-control" type="text" name="email" id="email"
                                                            ariadescribedby="email" value="<?=$account['email'][0]?>" />
                                                        <select name="email-format" id="email-format"
                                                            class="form-select">
                                                            <?php foreach ($emails as $key => $email) { 
                                                                if ($email == $account['email_ext']) {?>
                                                            <option value="<?=$email?>" selected><?=$email?></option>
                                                            <?php } else { ?>
                                                            <option value="<?=$email?>"><?=$email?></option>
                                                            <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-2">
                                                    <label for="whsNo">Whatsapp Number</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text text-primary">+92</span>
                                                        <input class="form-control phone_no_format" type="tel" name="whsNo" id="whsNo"
                                                            ariadescribedby="whsNo" value="<?=phone_no_format($account['whts_no'])?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-7 d-none cusDetail">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="mb-2">
                                                            <label for="guranterName">Guranter Name</label>
                                                            <input class="form-control" type="text" name="guranterName"
                                                                id="guranterName" ariadescribedby="guranterName" />
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="mb-2">
                                                            <label for="guranterCNIC">Guranter CNIC</label>
                                                            <input class="form-control" type="text" name="guranterCNIC"
                                                                id="guranterCNIC" ariadescribedby="guranterCNIC" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <div class="mb-2">
                                                    <label for="openingBalance">Opening Balance</label>
                                                    <input class="form-control comma" type="text" ariadescribedby="openingBalance"
                                                        value="<?=number_format($account['balance'])?>" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                    <div class="image-section px-4 pt-4">
                                            <img class="img-fluid rounded rounded-3" id="imgPreview"
                                                src="<?=(file_exists("uploads/acc-profiles/".$account['img']))?"uploads/acc-profiles/".$account['img']:"uploads/profiles/profile.png"?>" alt="" srcset="" />
                                            <div class="d-flex align-items-center justify-content-center">
                                                <input type="file" id="imgUpload" hidden />
                                                <input type="hidden" name="img" id="img" value="<?=(file_exists("uploads/acc-profiles/".$account['img']))?$account['img']:"uploads/profiles/profile.png"?>" hidden />
                                                <label class="btn btn-outline-gray-600 my-3" for="imgUpload">Upload
                                                    Image</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 d-none" id="expenseBankSection">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="mb-2">
                                            <label for="otherDetails">Other Details</label>
                                            <input class="form-control" type="text" name="otherDetails"
                                                id="otherDetails" ariadescribedby="otherDetails" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4 bankSection">
                                        <div class="mb-2">
                                            <label for="accNumber">Account Number</label>
                                            <input class="form-control" type="number" name="accNumber" id="accNumber"
                                                ariadescribedby="accNumber" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4 d-none expenseSection">
                                        <div class="mb-2">
                                            <label class="form-label" for="subGroup">Sub Group</label>
                                            <div class="input-group">
                                                <?php if(!empty($sub_groups)): ?>
                                                <select class="form-select" name="subGroup" id="subGroup">
                                                    <?php for ($i = 0; $i < count($sub_groups); $i++): ?>
                                                    <option value="<?php echo $sub_groups[$i]['id']; ?>">
                                                        <?php echo $sub_groups[$i]['name']; ?>
                                                    </option>
                                                    <?php endfor; ?>
                                                </select>
                                                <?php else: ?>
                                                <select class="form-select bg-white" disabled>
                                                    <option value="" selected>No Sub Group has been Added</option>
                                                </select>
                                                <?php endif; ?>
                                                <a class="input-group-text" data-bs-toggle="modal"
                                                    data-bs-target="#addSubGroup">
                                                    <i class="icon icon-xs" data-feather="plus"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-2">
                                            <label for="expenseBalance">Opening Balance</label>
                                            <input class="form-control comma" type="text" name="expenseBalance"
                                                id="expenseBalance" ariadescribedby="expenseBalance" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-2">
                                            <label for="paymentAction">Payment Action</label>
                                            <select class="form-select" name="paymentAction" id="paymentAction">
                                                <option value="" selected>Select Payment Action</option>
                                                <option value="receivable">Recievable</option>
                                                <option value="payable">Payable</option>
                                            </select>
                                            <div id="payActionFeedback" class="text-danger d-none">
                                                Select Payment Action
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 bankSection">
                                        <div class="mb-2">
                                            <label for="phoneNoBranch">Phone Number Branch</label>
                                            <input class="form-control" type="tel" name="phoneNoBranch"
                                                id="phoneNoBranch" ariadescribedby="phoneNoBranch" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-center d-none submit">
                                <input type="hidden" name="id" value="<?=$_GET['i']?>" />
                                <button class="btn btn-outline-gray-600 my-3" type="submit" name="submit">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include('temp/footer.temp.php'); ?>
    </main>

    <div class="modal fade" id="image-modal" tabindex="-1" aria-labelledby="image-modal" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-primary modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close theme-settings-close fs-6 ms-auto" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div class="modal-header mx-auto">
                    <p class="lead mb-0 text-white">Crop Profile Image</p>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-8 text-center">
                            <img class="img-fluid" id="image-preview" />
                        </div>
                        <div class="col-md-4">
                            <div class="mx-auto preview"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center pt-0 pb-3">
                    <button type="button" id="cropAndUpload" class="btn btn-sm btn-white text-tertiary">
                        Crop & Upload
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php include('temp/script.temp.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
    <?php if (isset($_GET['m'])) { ?>
        <?php if ($_GET['m'] == 'edit_false') { ?>
            notify("error", "Something's Wrong, Report Error ...");
        <?php } ?>
    <?php } ?>
    $(function() {
        $("#back").on("click", function() {
            window.history.back();
        });
        $("#accGroup").trigger("change");
    });

    const fileUpload = $("#imgUpload");

    fileUpload.change(function() {
        var file = this.files[0];
        console.log(file);
        if (file) {
            var fileName = this.value.split('\\').pop(); // get file name without path
            var fileExtension = fileName.split('.').pop().toLowerCase(); // get file extension
            if (fileExtension !== 'jpeg' && fileExtension !== 'jpg' && fileExtension !== 'png') {
                notify("error",
                    "You are using the wrong extension. Please use only JPEG, JPG, or PNG.");
                $(this).val(null); // reset the input
                return;
            }

            var image = document.getElementById('image-preview');
            var done = function(url) {
                image.src = url;
                $("#image-modal").modal('show');
            };

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function(e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }

            $("#image-modal").on('shown.bs.modal', function() {
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 3,
                    preview: '.preview'
                });
            }).on('hidden.bs.modal', function() {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            });
        }
    });

    $('#cropAndUpload').on('click', function() {
        canvas = cropper.getCroppedCanvas({
            width: 600,
            height: 600,
        });

        canvas.toBlob(function(blob) {
            url = URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function() {
                var base64data = reader.result;
                $("#img").val(base64data);
                $("#imgPreview").attr("src", base64data);
                $("#image-modal").modal("hide");
                fileUpload.val("");
            };
        });
    });

    $("#image-modal").on('hidden.bs.modal', function() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });

    $("#accGroup").on("change", function groupSelect() {
        let acc_type = $("#accGroup").val();
        let sections = {
            cus_seller_section: $("#CusSellerSection"),
            expense_section: $("#expenseBankSection"),
            cus_detail: $('.cusDetail'),
            bank_fields: $(".bankSection"),
            expense_fields: $(".expenseSection"),
            submit_btn: $(".submit")
        };
        let form = $("#accountsForm");

        form.find('input').val("");

        for (let section in sections) {
            sections[section].addClass("d-none");
        }

        sections.cus_seller_section.toggleClass("d-none", !(acc_type === "investor" || acc_type === "seller" ||
            acc_type === "customer" || acc_type === "staff"));
        sections.expense_section.toggleClass("d-none", !(acc_type === "expenses" || acc_type === "bank"));
        sections.submit_btn.toggleClass("d-none", !(acc_type === "investor" || acc_type === "seller" ||
            acc_type === "customer" || acc_type === "staff" || acc_type === "expenses" || acc_type ===
            "bank"));
        sections.cus_detail.toggleClass("d-none", !(acc_type === "customer"));
        sections.bank_fields.toggleClass("d-none", !(acc_type === "expenses"));
        sections.expense_fields.toggleClass("d-none", !(acc_type === "bank"));
    });
    </script>
</body>

</html>