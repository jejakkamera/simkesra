<div>
    <?php if ($__env->exists(data_get($setUp, 'footer.includeViewOnTop'))) echo $__env->make(data_get($setUp, 'footer.includeViewOnTop'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled(data_get($setUp, 'footer.perPage')) &&
            count(data_get($setUp, 'footer.perPageValues')) > 1 &&
            blank(data_get($setUp, 'footer.pagination'))): ?>
        <footer
                class="mt-50 pb-1 w-100 align-items-end px-1 d-flex flex-wrap justify-content-sm-center justify-content-md-between"
        >
            <div class="col-auto overflow-auto my-sm-2 my-md-0 ms-sm-0">
                <?php if(filled(data_get($setUp, 'footer.perPage')) && count(data_get($setUp, 'footer.perPageValues')) > 1): ?>
                    <div class="d-flex flex-lg-row align-items-center">
                        <label class="w-auto">
                            <select
                                    wire:model.live="setUp.footer.perPage"
                                    class="form-select <?php echo e(data_get($theme, 'footer.selectClass')); ?>"
                                    style="<?php echo e(data_get($theme, 'footer.selectStyle')); ?>"
                            >
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = data_get($setUp, 'footer.perPageValues'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($value == 0): ?>
                                            <?php echo e(trans('livewire-powergrid::datatable.labels.all')); ?>

                                        <?php else: ?>
                                            <?php echo e($value); ?>

                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </label>
                        <small class="ms-2 text-muted">
                            <?php echo e(trans('livewire-powergrid::datatable.labels.results_per_page')); ?>

                        </small>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="col-auto overflow-auto mt-2 mt-sm-0">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(method_exists($data, 'links')): ?>
                    <?php echo $data->links(data_get($setUp, 'footer.pagination') ?: powerGridThemeRoot() . '.pagination', [
                        'recordCount' => data_get($setUp, 'footer.recordCount'),
                    ]); ?>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </footer>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if ($__env->exists(data_get($setUp, 'footer.includeViewOnBottom'))) echo $__env->make(data_get($setUp, 'footer.includeViewOnBottom'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php /**PATH /Applications/MAMP/htdocs/83/simkesra/resources/views/vendor/livewire-powergrid/components/frameworks/bootstrap5/footer.blade.php ENDPATH**/ ?>