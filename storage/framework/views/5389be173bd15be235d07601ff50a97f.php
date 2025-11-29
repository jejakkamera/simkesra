<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'rowIndex' => 0,
    'childIndex' => null
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
    'rowIndex' => 0,
    'childIndex' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php echo $__env->renderWhen(isset($setUp['responsive']), powerGridThemeRoot() . '.toggle-detail-responsive', [
    'theme' => data_get($theme, 'table'),
    'rowId' => $rowId,
    'view' => data_get($setUp, 'detail.viewIcon') ?? null,
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>

<?php
    $ruleDetailView = data_get($rulesValues, 'detailView');
?>

<?php echo $__env->renderWhen(data_get($setUp, 'detail.showCollapseIcon'), powerGridThemeRoot() . '.toggle-detail', [
    'theme' => data_get($theme, 'table'),
    'view' => data_get($setUp, 'detail.viewIcon') ?? null,
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>

<?php echo $__env->renderWhen($radio, 'livewire-powergrid::components.radio-row', [
    'attribute' => $row->{$radioAttribute},
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>

<?php echo $__env->renderWhen($checkbox, 'livewire-powergrid::components.checkbox-row', [
    'attribute' => $row->{$checkboxAttribute},
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $content = $row->{$column->field} ?? '';
        $contentClassField = $column->contentClassField != '' ? $row->{$column->contentClassField} : '';
        $content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content ?? '');
        $field = $column->dataField != '' ? $column->dataField : $column->field;

        $contentClass = $column->contentClasses;

        if (is_array($column->contentClasses)) {
            $contentClass = array_key_exists($content, $column->contentClasses) ? $column->contentClasses[$content] : '';
        }
    ?>
    <td
        class="<?php echo \Illuminate\Support\Arr::toCssClasses([ data_get($theme, 'table.tdBodyClass'), $column->bodyClass]); ?>"
        style="<?php echo e($column->hidden === true ? 'display:none' : ''); ?>; <?php echo e(data_get($theme, 'table.tdBodyStyle'). ' ' . $column->bodyStyle ?? ''); ?>"
        wire:key="row-<?php echo e($column->field); ?>-<?php echo e($childIndex); ?>"
    >
        <div class="pg-actions">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty(data_get($row, 'actions')) && $column->isAction): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(method_exists($this, 'actionsFromView') && $actionsFromView = $this->actionsFromView($row)): ?>
                    <div wire:key="actions-view-<?php echo e(data_get($row, $primaryKey)); ?>">
                        <?php echo $actionsFromView; ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled(data_get($row, 'actions')) && $column->isAction): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = data_get($row, 'actions'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($action)): ?>
                        <span wire:key="action-<?php echo e(data_get($row, $primaryKey)); ?>-<?php echo e($key); ?>">
                            <?php echo $action; ?>

                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(data_get($column->editable, 'hasPermission') && !str_contains($field, '.')): ?>
            <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([$contentClassField, $contentClass]); ?>">
                <?php echo $__env->make(data_get($theme, 'editable.view') ?? null, ['editable' => $column->editable], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </span>
        <?php elseif(count($column->toggleable) > 0): ?>
            <?php
                $rules = $actionRulesClass->recoverFromAction($row, 'pg:rows');
                $toggleableRules = collect(data_get($rules, 'showHideToggleable', []));
                $showToggleable = $toggleableRules->isEmpty() || $toggleableRules->last() == 'show';
            ?>
            <?php echo $__env->make(data_get($theme, 'toggleable.view'), ['tableName' => $tableName], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([$contentClassField, $contentClass]); ?>">
                <div><?php echo $column->index ? $rowIndex : $content; ?></div>
            </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </td>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH /Applications/MAMP/htdocs/83/simkesra/resources/views/vendor/livewire-powergrid/components/row.blade.php ENDPATH**/ ?>