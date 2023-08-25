<script src="vendor/%40popperjs/core/dist/umd/popper.min.js"></script>
<script src="vendor/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="vendor/onscreen/dist/on-screen.umd.min.js"></script>
<script src="vendor/nouislider/distribute/nouislider.min.js"></script>
<script src="vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>
<script src="vendor/countup.js/dist/countUp.umd.js"></script>
<script src="vendor/apexcharts/dist/apexcharts.min.js"></script>
<script src="vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script>
<script src="vendor/simple-datatables/dist/umd/simple-datatables.js"></script>
<script src="vendor/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script>
<script src="vendor/fullcalendar/main.min.js"></script>
<script src="vendor/dropzone/dist/min/dropzone.min.js"></script>
<!-- <script src="vendor/choices.js/public/assets/scripts/choices.min.js"></script> -->
<script src="vendor/notyf/notyf.min.js"></script>
<script src="vendor/leaflet/dist/leaflet.js"></script>
<!-- <script src="vendor/svg-pan-zoom/dist/svg-pan-zoom.min.js"></script> -->
<script src="vendor/svgmap/dist/svgMap.min.js"></script>
<script src="vendor/simplebar/dist/simplebar.min.js"></script>
<script src="vendor/sortablejs/Sortable.min.js"></script>
<script src="assets/js/volt.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="vendor/eva-icons/eva.min.js"></script>

<script>
// $(window).load(function() {
//     $("#pulsar").fadeOut("slow");
// });
const notyf = new Notyf({
    position: {
        x: 'right',
        y: 'top',
    },
    types: [{
            type: 'warning',
            background: '#F0BC74',
            icon: {
                className: 'fa-solid fa-circle-warning',
                tagName: 'i',
                text: 'warning'
            },
            duration: 4000,
            dismissible: true
        },
        {
            type: 'info',
            background: '#1A232F',
            icon: {
                className: 'fa-solid fa-circle-check',
                tagName: 'i',
                text: '#fff'
            },
            duration: 4000,
            dismissible: true
        },
        {
            type: 'error',
            background: '#EC3D3E',
            icon: {
                className: 'fa-solid fa-circle-xmark',
                tagName: 'i',
                text: 'warning'
            },
            duration: 4000,
            dismissible: true
        }
    ]
});

const license_notfy = new Notyf({
    position: {
        x: 'right',
        y: 'top',
    },
    types: [{
        type: 'error',
        background: '#EC3D3E',
        icon: {
            className: 'fa-solid fa-circle-xmark',
            tagName: 'i',
            text: 'warning'
        },
        duration: 30000,
        dismissible: true
    }]
});

function licenseNotify(typ, msg) {
    license_notfy.open({
        type: typ,
        message: msg
    });
}

function notify(typ, msg) {
    notyf.open({
        type: typ,
        message: msg
    });
}

const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-primary',
        cancelButton: 'btn btn-gray'
    },
    buttonsStyling: false
});

function success_alert(alert_title, alert_text) {
    swalWithBootstrapButtons.fire({
        icon: 'success',
        title: alert_title,
        text: alert_text,
        showConfirmButton: true,
        timer: 3000
    })
}

function danger_alert(alert_title, alert_text) {
    swalWithBootstrapButtons.fire({
        icon: 'error',
        title: alert_title,
        text: alert_text,
        showConfirmButton: true,
        timer: 3000
    })
}

$(document).on('input', '.cnic_format', function(e) {
    setTimeout(() => {
        var number = $(this).val();
        if (number.length == 5) {
            $(this).val($(this).val() + '-');
        } else if (number.length == 13) {
            $(this).val($(this).val() + '-');
        }
    }, 200);
});

$(document).on('input', '.phone_no_format', function(e) {
    setTimeout(() => {
        var number = $(this).val().replace(/[^0-9]/g, ''); // Remove non-numeric characters
        var formattedNumber = '';

        if (number.length > 0) {
            formattedNumber += '(' + number.substring(0, 3) + ')';
        }
        if (number.length > 3) {
            formattedNumber += ' ' + number.substring(3, 6);
        }
        if (number.length > 6) {
            formattedNumber += '-' + number.substring(6, 10);
        }

        $(this).val(formattedNumber);
    }, 200);
});

$(document).on('input', '.number', function(e) {
  const inputValue = $(this).val();
  const numericValue = inputValue.replace(/[^0-9]/g, '');
  if (inputValue !== numericValue) {
    $(this).val(numericValue);
  }
});

$(document).on('input', '.comma', function(e) {
    setTimeout(() => {
        let amount = $(this);
        if (amount.val() != '') {
            amount.val(addComma(amount.val()));
        } else {
            amount.val("");
        }
    }, 0.3);
});
function addComma(value) {
    return value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

<?php
################################ Role Validation ################################
if ($_SESSION['loggedin'] == TRUE && validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-activity") === true) { #
################################ Role Validation ################################
?>
$(function() {
    $("#notifications").load("ajax/activity.php");
    setInterval(() => {
        $("#notifications").load("ajax/activity.php");
    }, 10000);
});
<?php } ?>
</script>