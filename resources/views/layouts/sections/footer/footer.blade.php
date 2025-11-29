@php
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
@endphp

<!-- Footer-->
<footer class="content-footer footer bg-footer-theme">
  <div class="{{ $containerFooter }}">
    <div class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column gap-2">
      <div class="text-center text-md-start">
        <small class="footer-text d-block">© {{ now()->year }} {{ $templateName }} · v{{ $templateVersion }}</small>
        <small class="footer-text">
          Dikelola oleh
          @if(!empty($creatorUrl))
            <a href="{{ $creatorUrl }}" target="_blank" class="footer-link text-primary fw-medium">{{ $creatorName }}</a>
          @else
            <span class="fw-medium">{{ $creatorName }}</span>
          @endif
        </small>
      </div>
      <div class="d-flex flex-wrap align-items-center justify-content-center gap-3">
        @foreach ($footerLinks as $link)
          @continue(empty($link['url']))
          <a href="{{ $link['url'] }}" target="_blank" class="footer-link">{{ $link['label'] }}</a>
        @endforeach
      </div>
    </div>
  </div>
</footer>
<!--/ Footer-->
