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
$conn = conn("localhost", "root", "", "pine-valley");             #
####################### Database Connection #######################

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-account") != true) {
    header("Location: account.all.php?message=account_add_not_allow");
    exit();
}
################################ Role Validation ################################

$title = "Add Account";
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
                                        <option value="expenses">Expenses</option>
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
                                                        name="fatherName" aria-describedby="fatherName" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="mb-2">
                                                    <label for="cnic">CNIC Number</label>
                                                    <input class="form-control cnic_format" type="text" name="cnic"
                                                        id="cnic" aria-describedby="CNICnumber" placeholder="33333-3333333-3" required />
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
                                                    <select name="city" id="city" class="form-select" required>
                                                        <option value="" selected>Select City</option>
                                                        <option value="Karachi">Karachi</option>
                                                        <option value="Lahore">Lahore</option>
                                                        <option value="Faisalabad">Faisalabad</option>
                                                        <option value="Rawalpindi">Rawalpindi</option>
                                                        <option value="Gujranwala">Gujranwala</option>
                                                        <option value="Peshawar">Peshawar</option>
                                                        <option value="Multan">Multan</option>
                                                        <option value="Hyderabad">Hyderabad</option>
                                                        <option value="Islamabad">Islamabad</option>
                                                        <option value="Quetta">Quetta</option>
                                                        <option value="Bahawalpur">Bahawalpur</option>
                                                        <option value="Sargodha">Sargodha</option>
                                                        <option value="Sialkot">Sialkot</option>
                                                        <option value="Sukkur">Sukkur</option>
                                                        <option value="LarkAdd Accountana">Larkana</option>
                                                        <option value="Rahim Yar Khan">Rahim Yar Khan</option>
                                                        <option value="Sheikhupura">Sheikhupura</option>
                                                        <option value="Jhang">Jhang</option>
                                                        <option value="Talagang">Talagang</option>
                                                        <option value="Dera Ghazi Khan">Dera Ghazi Khan</option>
                                                        <option value="Gujrat">Gujrat</option>
                                                        <option value="Sahiwal">Sahiwal</option>
                                                        <option value="Wah Cantonment">Wah Cantonment</option>
                                                        <option value="Mardan">Mardan</option>
                                                        <option value="Kasur">Kasur</option>
                                                        <option value="Okara">Okara</option>
                                                        <option value="Mingora">Mingora</option>
                                                        <option value="Nawabshah">Nawabshah</option>
                                                        <option value="Chiniot">Chiniot</option>
                                                        <option value="Kotri">Kotri</option>
                                                        <option value="Kāmoke">Kāmoke</option>
                                                        <option value="Hafizabad">Hafizabad</option>
                                                        <option value="Sadiqabad">Sadiqabad</option>
                                                        <option value="Mirpur Khas">Mirpur Khas</option>
                                                        <option value="Burewala">Burewala</option>
                                                        <option value="Kohat">Kohat</option>
                                                        <option value="Khanewal">Khanewal</option>
                                                        <option value="Dera Ismail Khan">Dera Ismail Khan</option>
                                                        <option value="Turbat">Turbat</option>
                                                        <option value="Muzaffargarh">Muzaffargarh</option>
                                                        <option value="Abbottabad">Abbottabad</option>
                                                        <option value="Mandi Bahauddin">Mandi Bahauddin</option>
                                                        <option value="Shikarpur">Shikarpur</option>
                                                        <option value="Jacobabad">Jacobabad</option>
                                                        <option value="Jhelum">Jhelum</option>
                                                        <option value="Khanpur">Khanpur</option>
                                                        <option value="Khairpur">Khairpur</option>
                                                        <option value="Khuzdar">Khuzdar</option>
                                                        <option value="Pakpattan">Pakpattan</option>
                                                        <option value="Hub">Hub</option>
                                                        <option value="Daska">Daska</option>
                                                        <option value="Gojra">Gojra</option>
                                                        <option value="Dadu">Dadu</option>
                                                        <option value="Muridke">Muridke</option>
                                                        <option value="Bahawalnagar">Bahawalnagar</option>
                                                        <option value="Samundri">Samundri</option>
                                                        <option value="Tando Allahyar">Tando Allahyar</option>
                                                        <option value="Tando Adam">Tando Adam</option>
                                                        <option value="Jaranwala">Jaranwala</option>
                                                        <option value="Chishtian">Chishtian</option>
                                                        <option value="Muzaffarabad">Muzaffarabad</option>
                                                        <option value="Attock">Attock</option>
                                                        <option value="Vehari">Vehari</option>
                                                        <option value="Kot Abdul Malik">Kot Abdul Malik</option>
                                                        <option value="Ferozwala">Ferozwala</option>
                                                        <option value="Chakwal">Chakwal</option>
                                                        <option value="Gujranwala Cantonment">Gujranwala Cantonment</option>
                                                        <option value="Kamalia">Kamalia</option>
                                                        <option value="Umerkot">Umerkot</option>
                                                        <option value="Ahmedpur East">Ahmedpur East</option>
                                                        <option value="Kot">Kot</option>
                                                        <option value="Wazirabad">Wazirabad</option>
                                                        <option value="Mansehra">Mansehra</option>
                                                        <option value="Layyah">Layyah</option>
                                                        <option value="Mirpur">Mirpur</option>
                                                        <option value="Swabi">Swabi</option>
                                                        <option value="Chaman">Chaman</option>
                                                        <option value="Taxila">Taxila</option>
                                                        <option value="Nowshera">Nowshera</option>
                                                        <option value="Khushab">Khushab</option>
                                                        <option value="Shahdadkot">Shahdadkot</option>
                                                        <option value="Mianwali">Mianwali</option>
                                                        <option value="Kabal">Kabal</option>
                                                        <option value="Lodhran">Lodhran</option>
                                                        <option value="Hasilpur">Hasilpur</option>
                                                        <option value="Charsadda">Charsadda</option>
                                                        <option value="Bhakkar">Bhakkar</option>
                                                        <option value="Badin">Badin</option>
                                                        <option value="Arif">Arif</option>
                                                        <option value="Ghotki">Ghotki</option>
                                                        <option value="Sambrial">Sambrial</option>
                                                        <option value="Jatoi">Jatoi</option>
                                                        <option value="Haroonabad">Haroonabad</option>
                                                        <option value="Daharki">Daharki</option>
                                                        <option value="Narowal">Narowal</option>
                                                        <option value="Tando Muhammad Khan">Tando Muhammad Khan</option>
                                                        <option value="Kamber Ali Khan">Kamber Ali Khan</option>
                                                        <option value="Mirpur Mathelo">Mirpur Mathelo</option>
                                                        <option value="Kandhkot">Kandhkot</option>
                                                        <option value="Bhalwal">Bhalwal</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-2">
                                                    <label for="province">State</label>
                                                    <select class="form-select" name="province" id="province" required>
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
                                                    <select name="country" id="country" class="form-select" required>
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
                                                            placeholder="(333) 333 3333" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-2">
                                                    <label for="email">E-mail</label>
                                                    <div class="input-group">
                                                    <input class="form-control" type="text" name="email" id="email"
                                                        ariadescribedby="email" />
                                                        <select name="email-format" id="email-format" class="form-select">
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
                                                            <label for="guranterCnic">Guranter CNIC</label>
                                                            <input class="form-control" type="text" name="guranterCnic"
                                                                id="guranterCnic" ariadescribedby="guranterCnic" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-5 d-none cusDetail">
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
                                            </div>
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
                                        <div class="image-section">
                                            <img class="img-fluid p-5 pb-0 rounded rounded-3" id="imgPreview"
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
                                                id="expenseBalance" ariadescribedby="expenseBalance" value="0" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-2">
                                            <label for="paymentAction">Payment Action</label>
                                            <select class="form-select" name="paymentAction" id="paymentAction">
                                                <option value="" selected>Select Payment Action</option>
                                                <option value="0">Recievable</option>
                                                <option value="1">Payable</option>
                                            </select>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
    $(function() {
        $("#category").trigger("change");
    });

    $("#docUpload").on("change", function(event) {
        let files = event.target.files;
        let fileDes = $("#docName");

        fileDes.html("");

        for (let i = 0; i < files.length; i++) {
            let file = files[i];
            let fileRow = $("<tr>");
            let fileDetailsColumn = $("<td>").text(i + 1);
            let fileColumn = $("<td>").text(file.name);
            fileRow.append(fileDetailsColumn);
            fileRow.append(fileColumn);
            fileDes.append(fileRow);
        }
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

    $("#category").on("change", function groupSelect() {
        let acc_type = $("#category").val();
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
    <?php } ?>
</body>

</html>