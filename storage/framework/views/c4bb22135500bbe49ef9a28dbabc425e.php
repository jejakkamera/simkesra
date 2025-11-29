<?php
$containerFooter = (isset($configData['contentLayout']) && $configData['contentLayout'] === 'compact') ? 'container-xxl' : 'container-fluid';
$templateName = config('variables.templateName', config('app.name', 'SIMKESRA'));
$templateVersion = config('variables.templateVersion', app()->version());
$creatorName = config('variables.creatorName', 'APPs');
$creatorUrl = config('variables.creatorUrl');
$documentationUrl = config('variables.documentation');
$changelogUrl = config('variables.changelog');
$supportUrl = config('variables.support');
$repositoryUrl = config('variables.repository');
$footerLinks = [
  ['label' => 'Dokumentasi', 'url' => $documentationUrl],
  ['label' => 'Changelog', 'url' => $changelogUrl],
  ['label' => 'Support', 'url' => $supportUrl],
  ['label' => 'Repository', 'url' => $repositoryUrl],
];
?>

<!-- Footer-->
<footer class="content-footer footer bg-footer-theme">
  <div class="<?php echo e($containerFooter); ?>">
    <div class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column gap-2">
      <div class="text-center text-md-start">
        <small class="footer-text d-block">© <?php echo e(now()->year); ?> <?php echo e($templateName); ?> · v<?php echo e($templateVersion); ?></small>
        <small class="footer-text">
          Dikelola oleh
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($creatorUrl)): ?>
            <a href="<?php echo e($creatorUrl); ?>" target="_blank" class="footer-link text-primary fw-medium"><?php echo e($creatorName); ?></a>
          <?php else: ?>
            <span class="fw-medium"><?php echo e($creatorName); ?></span>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </small>
      </div>
      <div class="d-flex flex-wrap align-items-center justify-content-center gap-3">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $footerLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php if(empty($link['url'])) continue; ?>
          <a href="<?php echo e($link['url']); ?>" target="_blank" class="footer-link"><?php echo e($link['label']); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </div>
    </div>
  </div>
</footer>
<!--/ Footer-->
<?php /**PATH /Applications/MAMP/htdocs/83/simkesra/resources/views/layouts/sections/footer/footer.blade.php ENDPATH**/ ?>