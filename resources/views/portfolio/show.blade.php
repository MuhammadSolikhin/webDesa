@extends('layouts.landing')

@push('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        #map {
            height: 500px;
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <!-- Page Title -->
    <div class="page-title" data-aos="fade">
      <div class="heading">
        <div class="container">
          <div class="row d-flex justify-content-center text-center">
            <div class="col-lg-8">
              <h1>{{ $portfolio->title }}</h1>
              <p class="mb-0">{{ $portfolio->category }}</p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="current">{{ $portfolio->title }}</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <!-- Portfolio Details Section -->
    <section id="portfolio-details" class="portfolio-details section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-8">
            <div class="portfolio-details-slider swiper init-swiper">
              <div class="align-items-center">
                @if(!empty($portfolio->image))
                    @php
                        $images = is_array($portfolio->image) ? $portfolio->image : [$portfolio->image];
                    @endphp
                    @if(count($images) > 1)
                        <div id="portfolioGallery" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                @foreach($images as $index => $img)
                                    <button type="button" data-bs-target="#portfolioGallery" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                                @endforeach
                            </div>
                            <div class="carousel-inner rounded shadow">
                                @foreach($images as $index => $img)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ Storage::url($img) }}" class="d-block w-100" alt="{{ $portfolio->title }}" style="object-fit: cover; max-height: 500px;">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#portfolioGallery" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#portfolioGallery" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    @else
                        @if(count($images) > 0)
                            <img src="{{ Storage::url($images[0]) }}" alt="{{ $portfolio->title }}" class="img-fluid rounded shadow" style="width: 100%; object-fit: cover; max-height: 500px;">
                        @endif
                    @endif
                @endif
              </div>
            </div>

            <div class="mt-5">
                <h3>Peta Lokasi</h3>
                @if($portfolio->map_file)
                    @php
                        $mapUrl = Storage::url($portfolio->map_file);
                        $isHtml = str_ends_with(strtolower($portfolio->map_file), '.html');
                    @endphp
                    
                    @if($isHtml)
                        <div id="map-container" class="map-container bg-light d-flex align-items-center justify-content-center flex-column" style="width: 100%; height: 500px; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); cursor: pointer; border: 2px dashed #ccc;" onclick="loadMap()">
                            <div class="text-center p-4">
                                <i class="bi bi-map" style="font-size: 3rem; color: #007bff;"></i>
                                <h4 class="mt-3">Klik untuk Memuat Peta Interaktif</h4>
                                <p class="text-muted">Mencegah loading lambat akibat banyaknya data peta.</p>
                                <button class="btn btn-primary mt-2">Tampilkan Peta</button>
                            </div>
                        </div>
                        <script>
                            function loadMap() {
                                const container = document.getElementById('map-container');
                                container.onclick = null;
                                container.style.border = 'none';
                                container.innerHTML = '<iframe src="{{ $mapUrl }}" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen></iframe>';
                            }
                        </script>
                    @else
                        <div id="map"></div>
                    @endif
                @else
                    <div class="alert alert-info mt-2">Peta belum tersedia untuk objek wisata ini.</div>
                @endif
            </div>
          </div>

          <div class="col-lg-4">
            <div class="portfolio-info" data-aos="fade-up" data-aos-delay="200">
              <h3>Informasi Objek Wisata</h3>
              <ul>
                <li><strong>Kategori</strong>: {{ Str::title(str_replace('filter-', '', $portfolio->category)) }}</li>
              </ul>
            </div>
            <div class="portfolio-description" data-aos="fade-up" data-aos-delay="300">
              <h2>Deskripsi</h2>
              <p>
                {!! nl2br(e($portfolio->description)) !!}
              </p>
            </div>
          </div>

        </div>

      </div>

    </section><!-- /Portfolio Details Section -->

@endsection

@push('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- Leaflet Omnivore for KML -->
    <script src="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.3.1/leaflet-omnivore.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if($portfolio->map_file && !str_ends_with(strtolower($portfolio->map_file), '.html'))
            // Default center
            var map = L.map('map').setView([-0.789275, 113.921327], 5);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var mapUrl = "{{ Storage::url($portfolio->map_file) }}";
            var ext = mapUrl.split('.').pop().toLowerCase();
            
            var customLayer = L.geoJson(null, {
                style: function(feature) {
                    return { color: '#007bff', weight: 3, fillColor: '#007bff', fillOpacity: 0.2 };
                },
                onEachFeature: function (feature, layer) {
                    if (feature.properties && feature.properties.name) {
                        layer.bindPopup(feature.properties.name);
                    }
                }
            });

            if (ext === 'kml') {
                var runLayer = omnivore.kml(mapUrl, null, customLayer)
                    .on('ready', function() { map.fitBounds(runLayer.getBounds()); })
                    .on('error', function(e) { console.error("KML Error: ", e); })
                    .addTo(map);
            } else if (ext === 'json' || ext === 'geojson') {
                fetch(mapUrl)
                    .then(response => response.json())
                    .then(data => {
                        customLayer.addData(data);
                        customLayer.addTo(map);
                        map.fitBounds(customLayer.getBounds());
                    })
                    .catch(err => console.error("GeoJSON Error: ", err));
            }
            @endif
        });
    </script>
@endpush
