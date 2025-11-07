const mix = require("laravel-mix");

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

mix.js("resources/js/app.js", "public/js")
    .vue()
    .sass("resources/sass/app.scss", "public/css")
    .scripts(
        [
            "resources/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js",
            "resources/adminlte/js/adminlte.min.js",
            "resources/adminlte/plugins/jquery-mousewheel/jquery.mousewheel.js",
            "resources/adminlte/plugins/raphael/raphael.min.js",
            "resources/adminlte/plugins/jquery-ui/jquery-ui.min.js",
            "resources/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js",
            "resources/adminlte/plugins/select2/js/select2.min.js",
            "resources/adminlte/plugins/datatables/jquery.dataTables.min.js",
            "resources/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js",
            "resources/adminlte/plugins/datatables-fixedheader/js/dataTables.fixedHeader.min.js",
            "resources/adminlte/plugins/datatables-fixedheader/js/fixedHeader.bootstrap4.min.js",
            "resources/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js",
            "resources/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js",
            "resources/adminlte/plugins/datatables-rowgroup/js/dataTables.rowGroup.min.js",
            "resources/adminlte/plugins/datatables-rowgroup/js/rowGroup.bootstrap4.min.js",
            "resources/adminlte/plugins/blockui/jquery.blockUI.js",
            "resources/adminlte/plugins/inputmask/jquery.inputmask.min.js",
            "resources/adminlte/plugins/inputmask/bindings/inputmask.binding.js",
        ],
        "public/js/vendor.js"
    )
    .styles(
        [
            "resources/adminlte/plugins/fontawesome-free/css/all.min.css",
            "resources/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css",
            "resources/adminlte/css/adminlte.min.css",
            "resources/adminlte/plugins/jquery-ui/jquery-ui.min.css",
            "resources/adminlte/plugins/select2/css/select2.min.css",
            "resources/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css",
            "resources/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css",
            "resources/adminlte/plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.min.css",
            "resources/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css",
            "resources/adminlte/plugins/datatables-rowgroup/css/rowGroup.bootstrap4.min.css",
        ],
        "public/css/vendor.css"
    )
    .copyDirectory(
        "resources/adminlte/plugins/fontawesome-free/webfonts",
        "public/webfonts"
    )
    .copyDirectory(
        "resources/adminlte/plugins/fontawesome-free/svgs",
        "public/svgs"
    )
    .copyDirectory(
        "resources/adminlte/plugins/fontawesome-free/sprites",
        "public/sprites"
    )
    .copyDirectory(
        "resources/adminlte/plugins/jquery-ui/images",
        "public/css/images"
    )
    .copyDirectory("resources/fonts", "public/fonts");
