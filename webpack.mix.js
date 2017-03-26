const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.styles([
        'resources/assets/css/bootstrap.min.css',
        'resources/assets/css/bootstrap-extend.min.css',
        'resources/assets/css/site.min.css',
    ], 'public/css/app.css')
    .styles([
        // 'resources/assets/vendor/animsition/animsition.css',
        'resources/assets/vendor/asscrollable/asScrollable.css',
        'resources/assets/vendor/switchery/switchery.css',
        'resources/assets/vendor/intro-js/introjs.css',
        'resources/assets/vendor/slidepanel/slidePanel.css',
        'resources/assets/vendor/flag-icon-css/flag-icon.css',
    ], 'public/css/plugins.css')
    .styles([
        'resources/assets/fonts/brand-icons/brand-icons.min.css',
        'resources/assets/fonts/web-icons/web-icons.min.css',
    ], 'public/css/fonts.css')
    .scripts([
        'resources/assets/vendor/babel-external-helpers/babel-external-helpers.js',
        'resources/assets/vendor/jquery/jquery.js',
        'resources/assets/vendor/tether/tether.js',
        'resources/assets/vendor/bootstrap/bootstrap.js',
        'resources/assets/vendor/animsition/animsition.js',
        'resources/assets/vendor/mousewheel/jquery.mousewheel.js',
        'resources/assets/vendor/asscrollbar/jquery-asScrollbar.js',
        'resources/assets/vendor/asscrollable/jquery-asScrollable.js',
        'resources/assets/vendor/ashoverscroll/jquery-asHoverScroll.js',
    ], 'public/js/core.js')
    .scripts([
        'resources/assets/vendor/switchery/switchery.min.js',
        'resources/assets/vendor/intro-js/intro.js',
        'resources/assets/vendor/screenfull/screenfull.js',
        'resources/assets/vendor/slidepanel/jquery-slidePanel.js',
    ], 'public/js/plugins.js')
    .scripts([
        'resources/assets/js/State.js',
        'resources/assets/js/Componenet.js',
        'resources/assets/js/Plugin.js',
        'resources/assets/js/Base.js',
        'resources/assets/js/Config.js',
        'resources/assets/js/Section/Menubar.js',
        'resources/assets/js/Section/Gridmenu.js',
        'resources/assets/js/Section/Sidebar.js',
        'resources/assets/js/Section/PageAside.js',
        'resources/assets/js/Plugin/menu.js',
        'resources/assets/js/config/colors.js',
        'resources/assets/js/config/tour.js',
    ], 'public/js/scripts.js')
    .scripts([
        'resources/assets/js/Site.js',
        'resources/assets/js/Plugin/asscrollable.js',
        'resources/assets/js/Plugin/slidepanel.js',
        'resources/assets/js/Plugin/switchery.js',
    ], 'public/js/page.js')
    .copy('resources/assets/fonts/brand-icons/fonts', 'public/fonts')
    .copy('resources/assets/fonts/web-icons/fonts', 'public/fonts');
