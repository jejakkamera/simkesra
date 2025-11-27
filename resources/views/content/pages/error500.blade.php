
<!-- Error -->
<div class="container-xxl container-p-y">
  <div class="misc-wrapper">
    <h1>500 - Internal Server Error</h1>
        <p>Something went wrong on our server. We're working to fix it!</p>
    <a href="{{url('/')}}" class="btn btn-primary mb-4">Back to home</a>
    <div class="mt-4">
      <img src="{{ asset('assets/img/illustrations/page-misc-error.png') }}" alt="page-misc-error" width="225" class="img-fluid">
    </div>
  </div>
</div>
<div class="container-fluid misc-bg-wrapper">
  <img src="{{ asset('assets/img/illustrations/bg-shape-image-'.$configData['style'].'.png') }}" alt="page-misc-error" data-app-light-img="illustrations/bg-shape-image-light.png" data-app-dark-img="illustrations/bg-shape-image-dark.png">
</div>
<!-- /Error -->
