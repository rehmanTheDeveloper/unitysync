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

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-account") != true) {
    header("Location: account.all.php?m=account_add_not_allow");
    exit();
}
################################ Role Validation ################################

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

$query = "SELECT * FROM `expense_sub_groups` WHERE `project_id` = '".$_SESSION['project']."';";
$sub_groups = fetch_Data($conn, $query);

$title = "Add Account";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('temp/head.temp.php'); ?>

    <link rel="stylesheet" href="vendor/cropperjs/dist/css/cropper.min.css" />
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
                        Add Account
                    </li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between w-100 flex-wrap align-items-center">
                <h1 class="h4 mb-0">Add Account</h1>
                <div>
                    <a href="account.all.php" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
                        All Accounts
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="fw-bolder">General Information</h6>
                        <form method="POST" action="comp/account.add.php" class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="mb-2">
                                    <label for="name">Account Title</label>
                                    <input class="form-control" id="name" name="name" type="text"
                                        placeholder="Enter Account Title ..." required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="category">Account Group</label>
                                    <select class="form-select" name="category" id="category" required>
                                        <option value="customer">Customer</option>
                                        <option value="seller">Seller</option>
                                        <option value="investor">Investor</option>
                                        <option value="staff">Staff</option>
                                        <option value="expense" selected>Expense</option>
                                        <option value="bank">Bank</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 d-none" id="CusSellerSection">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <div class="col-lg-2">
                                                <div class="mb-2">
                                                    <label for="prefix">Prefix</label>
                                                    <select class="form-select" name="prefix" id="prefix" required>
                                                        <option value="Mr.">Mr.</option>
                                                        <option value="Mrs.">Mrs.</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="mb-2">
                                                    <label for="fatherName">Father Name</label>
                                                    <input type="text" class="form-control" id="fatherName"
                                                        name="fatherName" aria-describedby="fatherName" />
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="mb-2">
                                                    <label for="cnic">CNIC Number</label>
                                                    <input class="form-control cnic_format" type="text" name="cnic"
                                                        id="cnic" aria-describedby="CNICnumber"
                                                        placeholder="33333-3333333-3" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-2">
                                                    <label for="address">Address</label>
                                                    <input class="form-control" type="text" name="address"
                                                        id="address" />
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-2">
                                                    <label for="city">City</label>
                                                    <select name="city" id="city" class="form-select">
                                                        <option value="" selected>Select City</option>
                                                        <?php foreach ($cities as $key => $city) { ?>
                                                        <option value="<?=$city?>"><?=$city?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-2">
                                                    <label for="province">State</label>
                                                    <select class="form-select" name="province" id="province">
                                                        <option value="" selected>Select State</option>
                                                        <option value="punjab">Punjab</option>
                                                        <option value="sindh">Sindh</option>
                                                        <option value="balochistan">Balochistan</option>
                                                        <option value="khyber pakhtunkhwa">Khyber Pakhtunkhwa</option>
                                                        <option value="kashmir">Kashmir</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-2">
                                                    <label for="country">Country</label>
                                                    <select name="country" id="country" class="form-select">
                                                        <option value="">Select country</option>
                                                        <option value="pakistan" selected>Pakistan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 d-none cusDetail">
                                                <div class="mb-2">
                                                    <label for="nextKin">Next Kin Name</label>
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
                                                    <label for="nextKinCnic">Next Kin CNIC</label>
                                                    <input class="form-control cnic_format" type="text"
                                                        name="nextKinCnic" id="nextKinCnic"
                                                        ariadescribedby="nextKinCnic" placeholder="33333-3333333-3" />
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-2">
                                                    <label for="phoneNo">Phone Number</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text text-primary">+92</span>
                                                        <input class="form-control phone_no_format" type="tel"
                                                            name="phoneNo" id="phoneNo" ariadescribedby="phoneNo"
                                                            placeholder="(333) 333 3333" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-2">
                                                    <label for="email">E-mail</label>
                                                    <div class="input-group">
                                                        <input class="form-control" type="text" name="email" id="email"
                                                            ariadescribedby="email" />
                                                        <select name="email-format" id="email-format"
                                                            class="form-select">
                                                            <option value="@gmail.com" selected>@gmail.com</option>
                                                            <option value="@outlook.com">@outlook.com</option>
                                                            <option value="@icloud.com">@icloud.com</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-2">
                                                    <label for="whtsNo">Whatsapp Number</label>
                                                    <input class="form-control phone_no_format" type="tel" name="whtsNo"
                                                        id="whtsNo" ariadescribedby="whtsNo"
                                                        placeholder="(333) 333 3333" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 d-none cusDetail">
                                                <div class="mb-2">
                                                    <label for="guranterName">Guranter Name</label>
                                                    <input class="form-control" type="text" name="guranterName"
                                                        id="guranterName" ariadescribedby="guranterName" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 d-none cusDetail">
                                                <div class="mb-2">
                                                    <label for="guranterCnic">Guranter CNIC</label>
                                                    <input class="form-control cnic_format" type="text" name="guranterCnic"
                                                        id="guranterCnic" ariadescribedby="guranterCnic" />
                                                </div>
                                            </div>
                                            <!-- <div class="col-lg-5 d-none cusDetail">
                                                <label for="">Attach Documents</label>
                                                <div class="card rounded p-2 mb-2">
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <input type="file" name="docUpload" id="docUpload" multiple
                                                            hidden />
                                                        <label class="btn btn-outline-gray-600 my-3"
                                                            for="docUpload">Upload</label>
                                                    </div>
                                                    <table class="table border-0">
                                                        <tbody id="docName">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div> -->
                                            <div class="col-lg-7">
                                                <div class="mb-2">
                                                    <label for="openingBalance">Opening Balance</label>
                                                    <input class="form-control comma" type="text" name="openingBalance"
                                                        id="openingBalance" ariadescribedby="openingBalance"
                                                        value="0" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="image-section px-4 pt-4">
                                            <img class="img-fluid rounded rounded-3" id="imgPreview"
                                                src="assets/img/profile.png" alt="" srcset="" />
                                            <div class="d-flex align-items-center justify-content-center">
                                                <input type="file" id="imgUpload" hidden />
                                                <input type="hidden" name="img" id="img" value="profile.png" hidden />
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
                                            <input class="form-control acc_no_format" type="text" name="accNumber"
                                                id="accNumber" ariadescribedby="accNumber" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4 d-none expenseSection">
                                        <div class="mb-2">
                                            <label class="form-label" for="subGroup">Sub Group</label>
                                            <div class="input-group">
                                                <?php if(!empty($sub_groups)) { ?>
                                                <select class="form-select" name="subGroup" id="subGroup">
                                                    <?php foreach ($sub_groups as $key => $group) { ?>
                                                    <option value="<?=$group['id']?>">
                                                        <?=$group['name']?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <?php } else { ?>
                                                <select class="form-select" disabled>
                                                    <option value="" selected>No Sub Group has been Added</option>
                                                </select>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-2">
                                            <label for="expenseBalance">Opening Balance</label>
                                            <input class="form-control comma" type="text" name="expenseBalance"
                                                id="expenseBalance" ariadescribedby="expenseBalance" value="0" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-2">
                                            <label for="paymentAction">Payment Action</label>
                                            <select class="form-select" name="paymentAction" id="paymentAction">
                                                <option value="" selected>Select Payment Action</option>
                                                <option value="debit">Debit</option>
                                                <option value="credit">Credit</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 bankSection">
                                        <div class="mb-2">
                                            <label for="accountBranch">Account Branch</label>
                                            <input class="form-control" type="text" name="accountBranch"
                                                id="accountBranch" ariadescribedby="accountBranch" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-center d-none submit">
                                <button class="btn btn-outline-gray-600 my-3" type="submit" name="submit">
                                    Submit
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
    <?php 
    ################################ Role Validation ################################
    if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-account") == true) { #
    ################################ Role Validation ################################
    ?>
    <script src="vendor/cropperjs/dist/js/cropper.min.js"></script>
    <script>
    $(function() {
        $("#category").trigger("change");
    });

    // $("#docUpload").on("change", function(event) {
    //     let files = event.target.files;
    //     let fileDes = $("#docName");

    //     fileDes.html("");

    //     for (let i = 0; i < files.length; i++) {
    //         let file = files[i];
    //         let fileRow = $("<tr>");
    //         let fileDetailsColumn = $("<td>").text(i + 1);
    //         let fileColumn = $("<td>").text(file.name);
    //         fileRow.append(fileDetailsColumn);
    //         fileRow.append(fileColumn);
    //         fileDes.append(fileRow);
    //     }
    // });

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

    $("#category").on("change", function groupSelect() {
        let acc_type = $("#category").val();
        let sections = {
            cus_seller_section: $("#CusSellerSection"),
            expense_bank_section: $("#expenseBankSection"),
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
        sections.expense_bank_section.toggleClass("d-none", !(acc_type === "expense" || acc_type === "bank"));
        sections.submit_btn.toggleClass("d-none", !(acc_type === "investor" || acc_type === "seller" ||
            acc_type === "customer" || acc_type === "staff" || acc_type === "expense" || acc_type ===
            "bank"));
        sections.cus_detail.toggleClass("d-none", !(acc_type === "customer"));
        sections.bank_fields.toggleClass("d-none", !(acc_type === "bank"));
        sections.expense_fields.toggleClass("d-none", !(acc_type === "expense"));
    });
    </script>
    <?php } ?>
</body>

</html>