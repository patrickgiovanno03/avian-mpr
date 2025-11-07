<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}?date={{ date('Ymd') }}"></script>
    <script src="{{ asset('js/vendor.js') }}?date={{ date('Ymd') }}"></script>
    <script src="{{ asset('js/inputmask.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Styles -->
    <style>
        @font-face {
            font-family: "Poppins";
            src: url("{{ asset('fonts/Poppins/Poppins-Regular.ttf') }}");
        }
        
        textarea {
        field-sizing: content;
        }
    </style>
    <link href="{{ asset('css/app.css') }}?date={{ date('Ymd') }}" rel="stylesheet">
    <link href="{{ asset('css/vendor.css') }}?date={{ date('Ymd') }}" rel="stylesheet">
    @yield('css')
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed avian">
    <div id="app" class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark navbar-avian">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <!-- <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Example Menu</a>
                </li> -->
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar elevation-4 sidebar-dark-avian">
            <!-- Brand Logo -->
            <a href="/" class="brand-link navbar-avian">
                <img src="{{ asset('images/logo.png') }}" alt="Mery Cookies Logo" class="brand-image">
                <span class="brand-text font-weight-light">{{ config('app.name', 'Laravel') }}</span>
            </a>

            <!-- Sidebar -->
            @include('layouts.sidebar')
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <div class="content">
                <main class="py-4">
                    @yield('content')
                </main>
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; {{ date('Y') }} <a href="https://www.avianbrands.com">Avian Brands</a>.</strong> All rights reserved.
        </footer>
    </div>
    <script>
        $(function() {
            bsCustomFileInput.init()

            $('.datepicker').datepicker({
                dateFormat: 'dd/mm/yy',
            });

            $('.select2').select2({
                placeholder: 'Pilih...',
                theme: 'bootstrap4',
            });

            $('.select2-multiple').select2({
                placeholder: 'Pilih...',
                theme: 'bootstrap4',
                multiple: true,
            });

            $('.select2-tags').select2({
                placeholder: 'Pilih...',
                theme: 'bootstrap4',
                tags: true,
            });

            $('.numeric').inputmask({
                alias: 'decimal',
                radixPoint: ',',
                groupSeparator: '.',
            });

            if (window.localStorage.getItem('sidebar')) {
                $('body').addClass('sidebar-collapse');

                $('.nav-item .nav-link p').removeClass('text-wrap');
            }

            $('a[data-widget=pushmenu]').on('click', function(e) {
                if (!$('body').hasClass('sidebar-collapse')) {
                    window.localStorage.setItem('sidebar', 'collapse');
                    $('.nav-item .nav-link p').removeClass('text-wrap');
                } else {
                    window.localStorage.removeItem('sidebar');
                    $('.nav-item .nav-link p').addClass('text-wrap');
                }
            });

            const updateNotificationCounter = function() {
                let unreadNotifications = parseInt(window.localStorage.getItem('unreadNotifications') || 0);
                $('.notification-unread-total').text(unreadNotifications > 9 ? '9+' : unreadNotifications);
            }

            updateNotificationCounter();

            $(window).on('storage', function() {
                updateNotificationCounter();
            });

            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
        });
    </script>
    @yield('js')
</body>

</html>