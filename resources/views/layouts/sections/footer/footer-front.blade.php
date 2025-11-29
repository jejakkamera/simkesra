@php
$templateName = config('variables.templateName', config('app.name', 'SIMKESRA'));
$templateVersion = config('variables.templateVersion', app()->version());
$templateDescription = 'Platform pengelolaan program kesejahteraan rakyat, lengkap dengan monitoring distribusi dan laporan real-time.';
$creatorName = config('variables.creatorName', 'APPs');
$creatorUrl = config('variables.creatorUrl');
$documentationUrl = config('variables.documentation');
$changelogUrl = config('variables.changelog');
$supportUrl = config('variables.support');
$contactEmail = config('mail.from.address', 'support@simkesra.local');
$quickLinks = [
  ['label' => 'Portal Admin', 'url' => url('/login')],
  ['label' => 'Registrasi Pengguna', 'url' => url('/register')],
  ['label' => 'Informasi Program', 'url' => url('/informasi')],
  ['label' => 'Pusat Bantuan', 'url' => url('/help-center')],
];
$resourceLinks = [
  ['label' => 'Dokumentasi', 'url' => $documentationUrl],
  ['label' => 'Changelog', 'url' => $changelogUrl],
  ['label' => 'Support', 'url' => $supportUrl],
  ['label' => 'Repository', 'url' => config('variables.repository')],
];
$footerLinks = array_filter($resourceLinks, fn($link) => !empty($link['url']));
@endphp

<!-- Footer: Start -->
<footer class="landing-footer bg-body footer-text">
  <div class="footer-top position-relative overflow-hidden z-1">
    <img src="{{ asset('assets/img/front-pages/backgrounds/footer-bg-'.$configData['style'].'.png') }}" alt="Ilustrasi latar footer" class="footer-bg banner-bg-img z-n1" data-app-light-img="front-pages/backgrounds/footer-bg-light.png" data-app-dark-img="front-pages/backgrounds/footer-bg-dark.png" />
    <div class="container">
      <div class="row gx-0 gy-4 g-md-5">
        <div class="col-lg-5">
          <a href="{{ url('/') }}" class="app-brand-link mb-4">
            <span class="app-brand-logo demo">@include('_partials.macros',['height'=>20,'withbg' => "fill: #fff;"])</span>
            <span class="app-brand-text demo footer-link fw-bold ms-2 ps-1">{{ $templateName }}</span>
          </a>
          <p class="footer-text footer-logo-description mb-4">{{ $templateDescription }}</p>
          <div class="footer-form">
            <span class="small d-block mb-1">Butuh bantuan cepat?</span>
            <a href="mailto:{{ $contactEmail }}" class="btn btn-outline-primary btn-sm shadow-none">{{ $contactEmail }}</a>
          </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
          <h6 class="footer-title mb-4">Navigasi</h6>
          <ul class="list-unstyled">
            @foreach ($quickLinks as $link)
              <li class="mb-3">
                <a href="{{ $link['url'] }}" class="footer-link">{{ $link['label'] }}</a>
              </li>
            @endforeach
          </ul>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
          <h6 class="footer-title mb-4">Sumber Daya</h6>
          <ul class="list-unstyled">
            @foreach ($resourceLinks as $link)
              @continue(empty($link['url']))
              <li class="mb-3">
                <a href="{{ $link['url'] }}" target="_blank" class="footer-link">{{ $link['label'] }}</a>
              </li>
            @endforeach
          </ul>
        </div>
        <div class="col-lg-2 col-md-4">
          <h6 class="footer-title mb-4">Tetap Terhubung</h6>
          <p class="footer-text mb-3">Ikuti perkembangan terbaru program SIMKESRA.</p>
          <div class="d-flex gap-3">
            <a href="{{ config('variables.facebookUrl') }}" class="footer-link" target="_blank">
              <img src="{{ asset('assets/img/front-pages/icons/facebook-'.$configData['style'].'.png') }}" alt="Facebook SIMKESRA" width="26" data-app-light-img="front-pages/icons/facebook-light.png" data-app-dark-img="front-pages/icons/facebook-dark.png" />
            </a>
            <a href="{{ config('variables.twitterUrl') }}" class="footer-link" target="_blank">
              <img src="{{ asset('assets/img/front-pages/icons/twitter-'.$configData['style'].'.png') }}" alt="Twitter SIMKESRA" width="26" data-app-light-img="front-pages/icons/twitter-light.png" data-app-dark-img="front-pages/icons/twitter-dark.png" />
            </a>
            <a href="{{ config('variables.instagramUrl') }}" class="footer-link" target="_blank">
              <img src="{{ asset('assets/img/front-pages/icons/instagram-'.$configData['style'].'.png') }}" alt="Instagram SIMKESRA" width="26" data-app-light-img="front-pages/icons/instagram-light.png" data-app-dark-img="front-pages/icons/instagram-dark.png" />
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-bottom py-3">
    <div class="container d-flex flex-wrap justify-content-between flex-md-row flex-column text-center text-md-start gap-2">
      <div>
        <span class="footer-text">© {{ now()->year }} {{ $templateName }} · v{{ $templateVersion }}</span>
        <span class="footer-text"> | </span>
        <span class="footer-text">Dikelola oleh
          @if(!empty($creatorUrl))
            <a href="{{ $creatorUrl }}" target="_blank" class="fw-medium text-white footer-link">{{ $creatorName }}</a>
          @else
            <span class="fw-medium">{{ $creatorName }}</span>
          @endif
        </span>
      </div>
      <div class="d-flex flex-wrap justify-content-center justify-content-md-end gap-3">
        @foreach ($footerLinks as $link)
          <a href="{{ $link['url'] }}" target="_blank" class="footer-link">{{ $link['label'] }}</a>
        @endforeach
      </div>
    </div>
  </div>
</footer>
<!-- Footer: End -->
