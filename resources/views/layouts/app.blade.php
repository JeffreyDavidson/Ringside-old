<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="description" content="bootstrap admin template">
        <meta name="author" content="">
        <title>Blank | Remark Admin Template</title>
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <link rel="shortcut icon" href="favicon.ico">
        <!-- Stylesheets -->
        <link href="/css/app.css" rel="stylesheet">
        <!-- Plugins -->
        <link href="/css/plugins.css" rel="stylesheet">
        <!-- Fonts -->
        <link href="/css/fonts.css" rel="stylesheet">
        <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
        <!--[if lt IE 9]>
        <script src="../../../global/vendor/html5shiv/html5shiv.min.js"></script>
        <![endif]-->
        <!--[if lt IE 10]>
        <script src="{{ asset('js/media.match.min.js') }}"></script>
        <script src="{{ asset('js/respond.min.js') }}"></script>
        <![endif]-->
        <!-- Scripts -->
        <script src="{{ asset('js/breakpoints.js') }}"></script>
        <script type="text/javascript">
            Breakpoints();
        </script>
        <script>
            window.Laravel = <?php echo json_encode([
				'csrfToken' => csrf_token(),
			]); ?>
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
        <div class="page" id="app">
            <div class="page-header">
                @yield('header')
            </div>
            <div class="page-content">
                @yield('content')
            </div>
        </div>
        <!-- End Page -->

        <!-- Footer -->
        @include('partials.footer')
        <!-- End Footer -->

        <!-- Core  -->
        <script src="{{ asset('js/core.js') }}" type="text/javascript"></script>
        <!-- Plugins -->
        <script src="{{ asset('js/plugins.js') }}" type="text/javascript"></script>
        <!-- Scripts -->
        <script src="{{ asset('js/scripts.js') }}" type="text/javascript"></script>
        <script type="text/javascript">
            Config.set('assets', '../../assets');
        </script>
        <!-- Page -->
        <script src="{{ asset('js/page.js') }}" type="text/javascript"></script>

{{--        <script src="{{ asset('js/manifest.js') }}" type="text/javascript"></script>--}}
        {{--<script src="{{ asset('js/vendor.js') }}" type="text/javascript"></script>--}}
        <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>

        <script type="text/javascript">
            (function(document, window, $) {
                'use strict';
                var Site = window.Site;
                $(document).ready(function() {
                    Site.run();
                });
            })(document, window, jQuery);
        </script>
        @yield('footer-scripts')
    </body>
</html>