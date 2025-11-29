<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'column' => null,
    'theme' => null,
    'enabledFilters' => null,
    'actions' => null,
    'dataField' => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'column' => null,
    'theme' => null,
    'enabledFilters' => null,
    'actions' => null,
    'dataField' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<?php
    $field = filled($column->dataField) ? $column->dataField : $column->field;

    $isFixedOnResponsive = false;

    if (isset($this->setUp['responsive'])) {
        if (in_array($field, data_get($this->setUp, 'responsive.fixedColumns'))) {
            $isFixedOnResponsive = true;
        }

        if ($column->fixedOnResponsive) {
            $isFixedOnResponsive = true;
        }
    }

    $sortOrder = isset($this->setUp['responsive']) ? data_get($this->setUp, "responsive.sortOrder.{$field}", null) : null;
?>
<th
    <?php if($sortOrder): ?> sort_order="<?php echo e($sortOrder); ?>" <?php endif; ?>
    class="<?php echo e(data_get($theme, 'table.thClass') . ' ' . $column->headerClass); ?>"
    <?php if($isFixedOnResponsive): ?> fixed <?php endif; ?>
    <?php if($column->sortable): ?> x-multisort-shift-click="<?php echo e($this->getId()); ?>" wire:click="sortBy('<?php echo e($field); ?>')" <?php endif; ?>
    style="<?php echo e($column->hidden === true ? 'display:none' : ''); ?>; width: max-content; <?php if($column->sortable): ?> cursor:pointer; <?php endif; ?> <?php echo e(data_get($theme, 'table.thStyle') . ' ' . $column->headerStyle); ?>"
>
    <div
        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
            'pl-[11px]' => !$column->sortable && isTailwind(),
            data_get($theme, 'cols.divClass'),
        ]); ?>"
        style="<?php echo e(data_get($theme, 'cols.divStyle')); ?>"
    >
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($column->sortable): ?>
            <span>
                <?php echo e($this->sortLabel($field)); ?>

            </span>
        <?php else: ?>
            <span style="width: 6px"></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <span data-value><?php echo $column->title; ?></span>
    </div>
</th>
<?php /**PATH /Applications/MAMP/htdocs/83/simkesra/resources/views/vendor/livewire-powergrid/components/cols.blade.php ENDPATH**/ ?>