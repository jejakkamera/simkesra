<div>
    <?php if ($__env->exists(data_get($setUp, 'header.includeViewOnTop'))) echo $__env->make(data_get($setUp, 'header.includeViewOnTop'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="dt--top-section">
        <div class="row">
            <div class="col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center">
                <?php echo $__env->make(powerGridThemeRoot() . '.header.actions', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <div class="me-1">
                    <?php echo $__env->renderWhen(data_get($setUp, 'exportable'), powerGridThemeRoot() . '.header.export', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
                </div>

                <?php echo $__env->make(powerGridThemeRoot() . '.header.toggle-columns', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php if ($__env->exists(powerGridThemeRoot() . '.header.soft-deletes')) echo $__env->make(powerGridThemeRoot() . '.header.soft-deletes', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <?php echo $__env->renderWhen(boolval(data_get($setUp, 'header.wireLoading')),
                    powerGridThemeRoot() . '.header.loading', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            </div>
            <div class="col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3">
                <?php echo $__env->make(powerGridThemeRoot() . '.header.search', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </div>
    <?php echo $__env->make(powerGridThemeRoot() . '.header.batch-exporting', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make(powerGridThemeRoot() . '.header.enabled-filters', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make(powerGridThemeRoot() . '.header.multi-sort', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php if ($__env->exists(data_get($setUp, 'header.includeViewOnBottom'))) echo $__env->make(data_get($setUp, 'header.includeViewOnBottom'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php if ($__env->exists(powerGridThemeRoot() . '.header.message-soft-deletes')) echo $__env->make(powerGridThemeRoot() . '.header.message-soft-deletes', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php /**PATH /Applications/MAMP/htdocs/83/simkesra/resources/views/vendor/livewire-powergrid/components/frameworks/bootstrap5/header.blade.php ENDPATH**/ ?>