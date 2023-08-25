<?php 
$arrow_up = '<svg class="icon icon-xs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>';
$arrow_down = '<svg class="icon icon-xs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>';
 ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=
$title?></title>
<meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
<meta name="title" content="<?=
$title?> - Plato">
<meta name="author" content="RehmanDev">
<!-- <meta name="description"
    content="Volt Pro is a Premium Bootstrap 5 Admin Dashboard featuring over 800 components, 10+ plugins and 20 example pages using Vanilla JS."> -->
<link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon/favicon-32x32.png">
<link type="text/css" href="vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
<link type="text/css" href="vendor/notyf/notyf.min.css" rel="stylesheet">
<link type="text/css" href="vendor/fullcalendar/main.min.css" rel="stylesheet">
<link type="text/css" href="vendor/apexcharts/dist/apexcharts.css" rel="stylesheet">
<link type="text/css" href="vendor/dropzone/dist/min/dropzone.min.css" rel="stylesheet">
<link type="text/css" href="vendor/choices.js/public/assets/styles/choices.min.css" rel="stylesheet">
<link type="text/css" href="vendor/leaflet/dist/leaflet.css" rel="stylesheet">
<link type="text/css" href="assets/css/volt.css" rel="stylesheet">
<style>
@media screen and (max-width: 500px) {
    .dropdown-menu-lg {
        min-width: 350px;
    }
}

@media screen and (min-width: 501px) {
    .dropdown-menu-lg {
        min-width: 400px;
    }
}

#accessDenied {
    animation: shake 1.5s;
}

.content {
    min-height: 100vh;
}

@keyframes shake {
    0% {
        transform: translate(1px, 1px) rotate(0deg);
    }

    10% {
        transform: translate(-1px, -2px) rotate(-1deg);
    }

    20% {
        transform: translate(-3px, 0px) rotate(1deg);
    }

    30% {
        transform: translate(3px, 2px) rotate(0deg);
    }

    40% {
        transform: translate(1px, -1px) rotate(1deg);
    }

    50% {
        transform: translate(-1px, 2px) rotate(-1deg);
    }

    60% {
        transform: translate(-3px, 1px) rotate(0deg);
    }

    70% {
        transform: translate(3px, 1px) rotate(-1deg);
    }

    80% {
        transform: translate(-1px, -1px) rotate(1deg);
    }

    90% {
        transform: translate(1px, 2px) rotate(0deg);
    }

    100% {
        transform: translate(0px, 0px) rotate(0deg);
    }
}

/* Loader (Pulsar) */
/* #pulsar {
    --uib-size: 40px;
    --uib-speed: 1.5s;
    --uib-color: black;

    position: absolute;
    left: 0px;
    top: 0px;
        left: 0px;
    top: 0px;
    width: var(--uib-size);
    height: var(--uib-size);
    z-index: 9999;
    color: var(--uib-color);
}

#pulsar::before,
#pulsar::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    border-radius: 50%;
    background-color: var(--uib-color);
    animation: pulse var(--uib-speed) ease-in-out infinite;
    transform: scale(0);
}

#pulsar::after {
    animation-delay: calc(var(--uib-speed) / -2);
}

@keyframes pulse {

    0%,
    100% {
        transform: scale(0);
        opacity: 1;
    }

    50% {
        transform: scale(1);
        opacity: 0.25;
    }
} */

/* Loader (Pulsar) */
</style>