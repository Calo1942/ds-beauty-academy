<?php

/**
 * Enlaces globales de CSS y JavaScript para el Front-End de DS Beauty Academy
 * Incluye Bootstrap 5.3, FontAwesome 6, Google Fonts, DataTables BS5 y SweetAlert2.
 */

$css_links = '
    <script>
        (function() {
            var savedTheme = localStorage.getItem("ds_theme") || "dark";
            document.documentElement.setAttribute("data-theme", savedTheme);
        })();
    </script>
    <link rel="stylesheet" href="assets/vendor/fonts/inter.css">
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/bootstrap-5.3.8/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/datatables/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="assets/vendor/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/vendor/animate/animate.min.css">
    <link rel="stylesheet" href="src/views/styles.css?v=2.3">
';

$scripts_links = '
    <script src="assets/vendor/jquery/jquery-3.7.1.min.js"></script>
    <script src="assets/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/vendor/datatables/dataTables.bootstrap5.min.js"></script>
    <script src="assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
';
