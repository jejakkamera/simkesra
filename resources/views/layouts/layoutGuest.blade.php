<!DOCTYPE html>
@php
$configData = Helper::appClasses();
$menuFixed = ($configData['layout'] === 'vertical') ? ($menuFixed ?? '') : (($configData['layout'] === 'front') ? '' : $configData['headerType']);
$navbarType = ($configData['layout'] === 'vertical') ? ($configData['navbarType'] ?? '') : (($configData['layout'] === 'front') ? 'layout-navbar-fixed': '');
$isFront = ($isFront ?? '') == true ? 'Front' : '';
$contentLayout = (isset($container) ? (($container === 'container-xxl') ? "layout-compact" : "layout-wide") : "");
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<link rel="icon" type="image/x-icon" href="{{ asset($school_logo) }}" />


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Apps</title>
    @include('layouts/sections/styles' . $isFront)
    @include('layouts/sections/scriptsIncludes' . $isFront)

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite([
      'resources/assets/vendor/libs/@form-validation/form-validation.scss'
    ])
    @vite([
      'resources/assets/vendor/scss/pages/page-auth.scss'
    ])
    @vite([
      'resources/assets/vendor/libs/@form-validation/popular.js',
      'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
      'resources/assets/vendor/libs/@form-validation/auto-focus.js'
    ])
    @vite([
      'resources/assets/js/pages-auth.js'
    ])
    <!-- Livewire Styles -->
    @livewireStyles
    @filamentStyles

    {{-- Include core + vendor Styles --}}
</head>
<body class="vertical-layout vertical-menu-modern 1-column navbar-floating footer-static bg-full-screen-image blank-page blank-page">

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                {{ $slot }}
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- Livewire Scripts -->
    @livewireScripts
    @filamentScripts
    @include('layouts/sections/scripts' . $isFront)

    {{-- Include core + vendor Scripts --}}
</body>
</html>
