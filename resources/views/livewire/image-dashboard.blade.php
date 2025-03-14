<div>
    <div id="carouselExample" class="carousel slide col-md-12" data-bs-ride="carousel">
        <ol class="carousel-indicators">
            @foreach ($downloads as $index => $file_path)
                <li data-bs-target="#carouselExample" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></li>
            @endforeach
        </ol>
        <div class="carousel-inner">
            @foreach ($downloads as $index => $image)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <img class="d-block w-100" src="{{ url("/storage/{$image['file_path']}") }}" alt="{{ $image['description'] }}" />
                    <div class="carousel-caption d-none d-md-block">
                        <p>{{ $image['description'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#carouselExample" role="button" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExample" role="button" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </a>
      </div>
</div>
