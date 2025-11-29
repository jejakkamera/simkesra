<?php
    $actionController = new \PowerComponents\LivewirePowerGrid\Components\Actions\ActionsController($this, collect());
    $headers = $this->header();
    $actions = $actionController->execute($headers);
?>
<div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="<?php echo \Illuminate\Support\Arr::toCssClasses(['me-1' => $loop->last, 'btn-group']); ?>">
            <?php echo $action; ?>

        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH /Applications/MAMP/htdocs/83/simkesra/resources/views/vendor/livewire-powergrid/components/frameworks/bootstrap5/header/actions.blade.php ENDPATH**/ ?>