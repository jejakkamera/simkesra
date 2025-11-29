<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($enabledFilters)): ?>
    <div
        class="col-md-12 d-flex table-responsive"
        style="margin-top: 5px"
    >
        <?php if(count($enabledFilters) > 1): ?>
            <div
                wire:click.prevent="clearAllFilters()"
                style="cursor: pointer; padding-right: 4px"
            >
                <span
                    class="badge rounded-pill bg-secondary"><?php echo e(trans('livewire-powergrid::datatable.buttons.clear_all_filters')); ?>

                    <svg
                        width="10"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 8 8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-width="1.5"
                            d="M1 1l6 6m0-6L1 7"
                        />
                    </svg>
                </span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $enabledFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($filter['label'])): ?>
                <div
                    wire:click.prevent="clearFilter('<?php echo e($filter['field']); ?>')"
                    style="cursor: pointer; padding-right: 4px"
                >
                    <span class="badge rounded-pill bg-light text-dark"><?php echo e($filter['label']); ?>

                        <svg
                            width="10"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 8 8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-width="1.5"
                                d="M1 1l6 6m0-6L1 7"
                            />
                        </svg>
                    </span>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH /Applications/MAMP/htdocs/83/simkesra/resources/views/vendor/livewire-powergrid/components/frameworks/bootstrap5/header/enabled-filters.blade.php ENDPATH**/ ?>