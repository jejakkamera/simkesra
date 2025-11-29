<div <?php if($deferLoading): ?> wire:init="fetchDatasource" <?php endif; ?>>
    <div class="col-md-12">
        <?php echo $__env->make(data_get($theme, 'layout.header'), [
            'enabledFilters' => $enabledFilters,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
    <div
        class="<?php echo e(data_get($theme, 'table.divClass')); ?>"
        style="<?php echo e(data_get($theme, 'table.divStyle')); ?>"
    >
        <?php echo $__env->make($table, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
    <div class="row">
        <div class="col-12 overflow-auto">
            <?php echo $__env->make(data_get($theme, 'footer.view'), ['theme' => $theme], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
</div>
<?php /**PATH /Applications/MAMP/htdocs/83/simkesra/resources/views/vendor/livewire-powergrid/components/frameworks/bootstrap5/table-base.blade.php ENDPATH**/ ?>