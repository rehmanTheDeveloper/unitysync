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

$title = "Edit Account";
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
                        Edit Account
                    </li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between w-100 flex-wrap align-items-center">
                <h1 class="h4 mb-0">Abdul Rehman</h1>
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
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="mb-2">
                                    <label for="accTitle">Account Title</label>
                                    <input class="form-control" id="accTitle" name="accTitle" type="text"
                                        placeholder="Enter Account Title ..." />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="accGroup">Account Group</label>
                                    <select class="form-select" name="accGroup" id="accGroup">
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
                                                    <label for="title">Title</label>
                                                    <select class="form-select" name="title" id="title">
                                                        <option value="Mr.">Mr.</option>
                                                        <option value="Mrs.">Mrs.</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="mb-2">
                                                    <label for="fName">Father Name</label>
                                                    <input type="text" class="form-control" id="fName" name="fName"
                                                        aria-describedby="fatherName" />
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="mb-2">
                                                    <label for="cnicNum">CNIC Number</label>
                                                    <input class="form-control cnic_format" type="text" name="cnicNum"
                                                        id="cnicNum" aria-describedby="CNICnumber" />
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
                                                    <input class="form-control" type="text" name="city" id="city"
                                                        ariadescribedby="city" />
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-2">
                                                    <label for="province">Province</label>
                                                    <input class="form-control" type="text" name="province"
                                                        id="province" ariadescribedby="province" />
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-2">
                                                    <label for="country">country</label>
                                                    <input class="form-control" type="text" name="country" id="country"
                                                        ariadescribedby="country" />
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
                                                    <input class="form-control" type="tel" name="phoneNo" id="phoneNo"
                                                        ariadescribedby="phoneNo" />
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-2">
                                                    <label for="email">E-mail</label>
                                                    <input class="form-control" type="email" name="email" id="email"
                                                        ariadescribedby="email" />
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-2">
                                                    <label for="whsNo">Whatsapp Number</label>
                                                    <input class="form-control" type="tel" name="whsNo" id="whsNo"
                                                        ariadescribedby="whsNo" />
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
                                                    <input class="form-control comma" type="text" name="openingBalance"
                                                        id="openingBalance" ariadescribedby="openingBalance"
                                                        value="0" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="image-section">
                                            <img class="img-fluid p-5 pb-0" id="imgPreview" src="assets/img/profile.png"
                                                alt="" srcset="" />
                                            <div class="d-flex align-items-center justify-content-center">
                                                <input type="file" name="imgUpload" id="imgUpload" hidden />
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
                                <button class="btn btn-outline-gray-600 my-3" type="submit" name="submit">
                                    Save Changes
                                </button>
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
        $("#accGroup").trigger("change");
    });

    const fileUpload = $("#imgUpload");
    const preview = $("#imgPreview");

    fileUpload.on("change", function() {
        var fileSize = this.files[0].size; // in bytes
        var maxSize = 2000000; // in bytes
        var fileName = this.value.split('\\').pop(); // get file name without path
        var fileExtension = fileName.split('.').pop().toLowerCase(); // get file extension
        if (fileExtension !== 'jpeg' && fileExtension !== 'jpg' && fileExtension !== 'png') {
            alert("You are using the wrong extension. Please use only JPEG, JPG, or PNG.");
            preview.attr("src", "assets/img/profile.png");
            $(this).val(null); // reset the input
            return;
        }
        if (fileSize > maxSize) {
            alert("The size of the file is larger than 2 MB.");
            preview.attr("src", "assets/img/profile.png");
            $(this).val(null); // reset the input
            return;
        }
        var file = this.files[0];
        var reader = new FileReader();
        reader.addEventListener("load", function() {
            preview.attr("src", reader.result);
        });
        reader.readAsDataURL(file);

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