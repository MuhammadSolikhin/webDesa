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
                @if($portfolio->image)
                    <img src="{{ Storage::url($portfolio->image) }}" alt="{{ $portfolio->title }}" class="img-fluid rounded shadow" style="width: 100%; object-fit: cover;">
                @endif
              </div>
            </div>
            
            <div class="mt-5">
                <h3>Peta Lokasi</h3>
                <div id="map"></div>
                @if(!$portfolio->kml_file)
                    <div class="alert alert-info mt-2">Peta KML belum tersedia untuk objek wisata ini.</div>
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
            // Default center if no KML (e.g., center of Indonesia or your specific region)
            var map = L.map('map').setView([-0.789275, 113.921327], 5);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            @if($portfolio->kml_file)
                var kmlUrl = "{{ Storage::url($portfolio->kml_file) }}";
                
                // Add KML layer using omnivore
                var customLayer = L.geoJson(null, {
                    style: function(feature) {
                        return { color: '#007bff', weight: 3 };
                    },
                    onEachFeature: function (feature, layer) {
                        if (feature.properties && feature.properties.name) {
                            layer.bindPopup(feature.properties.name);
                        }
                    }
                });

                var runLayer = omnivore.kml(kmlUrl, null, customLayer)
                    .on('ready', function() {
                        map.fitBounds(runLayer.getBounds());
                    })
                    .on('error', function(e) {
                        console.error("Error loading KML: ", e);
                        document.getElementById('map').insertAdjacentHTML('afterend', '<div class="alert alert-danger mt-2">Gagal memuat file KML.</div>');
                    })
                    .addTo(map);
            @endif
        });
    </script>
@endpush
