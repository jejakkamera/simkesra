
@vite([
'resources/assets/vendor/libs/jquery/jquery.js',
'resources/assets/vendor/libs/popper/popper.js',
'resources/assets/vendor/js/bootstrap.js',
'resources/assets/vendor/libs/bs-stepper/bs-stepper.js',
'resources/assets/vendor/libs/node-waves/node-waves.js',
'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js',
'resources/assets/vendor/libs/hammer/hammer.js',
'resources/assets/vendor/libs/typeahead-js/typeahead.js',
'resources/assets/vendor/libs/typeahead-js/typeahead.js',
'public/js/filament/filament/echo.js',
'public/js/filament/filament/app.js',
'resources/assets/vendor/js/menu.js'])

@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
@vite(['resources/assets/js/main.js'])

<!-- END: Theme JS-->
<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->
<!-- BEGIN: Page JS-->
@yield('page-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/pivot.min.js"></script>

<!-- END: Page JS-->
