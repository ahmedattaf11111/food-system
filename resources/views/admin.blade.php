<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>Grostore</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/images/admin-logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/admin-logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/admin-logo.png">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" id="core" type="text/css" href="/vendors/styles/core.css">
    <link rel="stylesheet" id="icon" type="text/css" href="/vendors/styles/icon-font.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Readex+Pro:wght@200;300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/src/plugins/dropzone/src/dropzone.css">
    <link rel="stylesheet" id="style" type="text/css" href="/vendors/styles/style.css">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <style>
        .footer-wrap {
            background: #fff;
            border-radius: 5px;
        }

        .header {
            /* #dfe3e8 */
            box-shadow: 0 0 0px rgba(0, 0, 0, 0) !important;
            border-bottom: 1px solid #d4d4d4;
        }

        .swal2-styled {
            width: 54px;
            margin: 0 5px !important;
            text-align: center;
        }

        /* width */
        ::-webkit-scrollbar {
            width: 3px;
            height: 3px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: transparent;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #919eab;
        }

        .sidebarscroll::-webkit-scrollbar-thumb {
            background: #919eab;

        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .sidebarscroll::-webkit-scrollbar-thumb:hover {
            background: #919eab;
        }

        .swal2-cancel,
        .swal2-confirm {
            background: #f4f6f8 !important;
            color: #545454 !important;
            border: 1px solid #dee2e6 !important;
            font-size: 14px !important;
        }

        .toast-content * {
            font-size: 14px !important;
        }

        .swal2-confirm {
            justify-content: center;
            display: flex !important;
        }

        .swal2-popup * {
            font-size: 14px !important;
        }
    </style>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-119386393-1');
    </script>
</head>

<body id="body" dir="ltr">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <div id="app">
        <app></app>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="/vendors/scripts/core.js"></script>
    <script src="/vendors/scripts/script.min.js"></script>
    <!-- <script src="/vendors/scripts/process.js"></script> -->
    <!-- <script src="/vendors/scripts/layout-settings.js"></script> -->
    <!-- <script src="/src/plugins/apexcharts/apexcharts.min.js"></script> -->
    <!-- <script src="/vendors/scripts/dashboard.js"></script> -->
    <script src="/src/plugins/dropzone/src/dropzone.js"></script>

</body>

</html>