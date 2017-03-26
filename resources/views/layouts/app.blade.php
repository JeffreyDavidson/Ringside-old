<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="bootstrap admin template">
    <meta name="author" content="">
    <title>Blank | Remark Admin Template</title>
    <link rel="apple-touch-icon" href="../../assets/images/apple-touch-icon.png">
    <link rel="shortcut icon" href="../../assets/images/favicon.ico">
    <!-- Stylesheets -->
    <link href="/css/app.css" rel="stylesheet">
    <!-- Plugins -->
    <link href="/css/plugins.css" rel="stylesheet">
    <!-- Fonts -->
    <link href="/css/fonts.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../global/fonts/web-icons/web-icons.min.css">
    <link rel="stylesheet" href="../../../global/fonts/brand-icons/brand-icons.min.css">
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
    <!--[if lt IE 9]>
    <script src="../../../global/vendor/html5shiv/html5shiv.min.js"></script>
    <![endif]-->
    <!--[if lt IE 10]>
    <script src="../../../global/vendor/media-match/media.match.min.js"></script>
    <script src="../../../global/vendor/respond/respond.min.js"></script>
    <![endif]-->
    <!-- Scripts -->
    <script src="../../../global/vendor/breakpoints/breakpoints.js"></script>
    <script>
        Breakpoints();
    </script>
</head>
<body class="animsition">
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

@include('partials.navbar')

@include('partials.menubar')

@include('partials.gridmenu')

<!-- Page -->
<div class="page">
    <div class="page-content">
        @yield('content')
    </div>
</div>
<!-- End Page -->

<!-- Footer -->
@include('partials.footer')
<!-- End Footer -->

<!-- Core  -->
<script src="/js/core.js"></script>
<!-- Plugins -->
<script src="/js/plugins.js"></script>
<!-- Scripts -->
<script src="/js/scripts.js"></script>
<script>
    Config.set('assets', '../../assets');
</script>
<!-- Page -->
<script src="/js/page.js"></script>

<script>
    (function(document, window, $) {
        'use strict';
        var Site = window.Site;
        $(document).ready(function() {
            Site.run();
        });
    })(document, window, jQuery);
</script>
</body>
</html>