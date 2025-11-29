<!DOCTYPE html>
<?php
$configData = Helper::appClasses();
$menuFixed = ($configData['layout'] === 'vertical') ? ($menuFixed ?? '') : (($configData['layout'] === 'front') ? '' : $configData['headerType']);
$navbarType = ($configData['layout'] === 'vertical') ? ($configData['navbarType'] ?? '') : (($configData['layout'] === 'front') ? 'layout-navbar-fixed': '');
$isFront = ($isFront ?? '') == true ? 'Front' : '';
$contentLayout = (isset($container) ? (($container === 'container-xxl') ? "layout-compact" : "layout-wide") : "");
?>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<link rel="icon" type="image/x-icon" href="<?php echo e(asset($school_logo)); ?>" />


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Apps</title>
    <?php echo $__env->make('layouts/sections/styles' . $isFront, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('layouts/sections/scriptsIncludes' . $isFront, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Vite -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo app('Illuminate\Foundation\Vite')([
      'resources/assets/vendor/libs/@form-validation/form-validation.scss'
    ]); ?>
    <?php echo app('Illuminate\Foundation\Vite')([
      'resources/assets/vendor/scss/pages/page-auth.scss'
    ]); ?>
    <?php echo app('Illuminate\Foundation\Vite')([
      'resources/assets/vendor/libs/@form-validation/popular.js',
      'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
      'resources/assets/vendor/libs/@form-validation/auto-focus.js'
    ]); ?>
    <?php echo app('Illuminate\Foundation\Vite')([
      'resources/assets/js/pages-auth.js'
    ]); ?>
    <!-- Livewire Styles -->
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <?php echo \Filament\Support\Facades\FilamentAsset::renderStyles() ?>

    
</head>
<body class="vertical-layout vertical-menu-modern 1-column navbar-floating footer-static bg-full-screen-image blank-page blank-page">

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <?php echo e($slot); ?>

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- Livewire Scripts -->
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <?php echo \Filament\Support\Facades\FilamentAsset::renderScripts() ?>
    <?php echo $__env->make('layouts/sections/scripts' . $isFront, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
</body>
</html>
<?php /**PATH /Applications/MAMP/htdocs/83/simkesra/resources/views/layouts/layoutGuest.blade.php ENDPATH**/ ?>