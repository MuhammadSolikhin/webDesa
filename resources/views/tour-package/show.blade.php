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
        
        /* Fix for Leaflet layer control whitespace issue */
        .leaflet-control-layers-expanded {
            padding: 8px 10px !important;
            border-radius: 6px !important;
        }
        .leaflet-control-layers-list {
            margin-bottom: 0 !important;
        }
        .leaflet-control-layers-base label {
            display: flex !important;
            align-items: center !important;
            margin-bottom: 4px !important;
            font-size: 13px !important;
            line-height: 1.2 !important;
        }
        .leaflet-control-layers-base label:last-child {
            margin-bottom: 0 !important;
        }
        .leaflet-control-layers-base label input[type="radio"] {
            margin-right: 6px !important;
            margin-top: 0 !important;
            width: auto !important;
            height: auto !important;
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
              <h1>{{ $tourPackage->name }}</h1>
              <p class="mb-0">Detail Paket Wisata dan Rute Perjalanan</p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="current">{{ $tourPackage->name }}</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <!-- Tour Package Details Section -->
    <section id="portfolio-details" class="portfolio-details section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4 mb-5">
          <div class="col-lg-8">
            <div class="portfolio-details-slider swiper init-swiper">
              <div class="align-items-center">
                @if($tourPackage->image)
                    <img src="{{ asset('storage/' . $tourPackage->image) }}" alt="{{ $tourPackage->name }}" class="img-fluid rounded shadow" style="width: 100%; object-fit: cover; max-height: 500px;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center rounded shadow" style="height: 500px;">
                        <span class="text-muted"><i class="bi bi-image" style="font-size: 5rem;"></i></span>
                    </div>
                @endif
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="portfolio-info" data-aos="fade-up" data-aos-delay="200">
              <h3>Informasi Paket</h3>
              <ul>
                <li><strong>Harga</strong>: <span class="fs-4 fw-bold text-success">{{ $tourPackage->price ? 'Rp ' . number_format($tourPackage->price, 0, ',', '.') : 'Gratis' }}</span></li>
              </ul>
              
              <div class="mt-4">
                <a href="{{ route('checkout.show', $tourPackage) }}" class="btn btn-lg w-100 text-white rounded-pill" style="background-color: var(--accent-color);">
                  <i class="bi bi-cart-plus me-2"></i> Pesan Sekarang
                </a>
              </div>
            </div>
            <div class="portfolio-description mt-4" data-aos="fade-up" data-aos-delay="300">
              <h2>Deskripsi</h2>
              <p>
                {!! nl2br(e($tourPackage->description)) !!}
              </p>
            </div>
          </div>
        </div>

        <div class="row gy-4">
          <div class="col-lg-8">
            <div>
                <h3>Peta dan Rute Perjalanan</h3>
                <div id="map"></div>
                @if(!$tourPackage->kml_file)
                    <div class="alert alert-info mt-2">Peta KML belum tersedia untuk paket wisata ini.</div>
                @endif
            </div>
          </div>

          <div class="col-lg-4">
            <div id="kml-list-container" style="display: none;" data-aos="fade-up" data-aos-delay="400">
              <h3 class="fs-4">Daftar Lokasi & Jalur</h3>
              <div class="list-group shadow-sm mt-3" id="feature-list" style="max-height: 500px; overflow-y: auto;">
                <!-- Populate via JS -->
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Tour Package Details Section -->

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

            var streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            });

            var satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
            });
            
            streetMap.addTo(map);

            var baseMaps = {
                "Peta Jalan": streetMap,
                "Satelit": satelliteMap
            };
            
            L.control.layers(baseMaps).addTo(map);

            @if($tourPackage->kml_file)
                var kmlUrl = "{{ Storage::url($tourPackage->kml_file) }}";
                
                // Add KML layer using omnivore
                var customLayer = L.geoJson(null, {
                    style: function(feature) {
                        return { color: '#007bff', weight: 3 };
                    },
                    onEachFeature: function (feature, layer) {
                        if (feature.properties) {
                            var popupContent = '<b>' + (feature.properties.name || 'Lokasi') + '</b>';
                            if (feature.properties.description) {
                                popupContent += '<br><div class="mt-2 text-muted" style="max-height: 150px; overflow-y: auto;">' + feature.properties.description + '</div>';
                            }
                            layer.bindPopup(popupContent);
                        }
                    }
                });

                var runLayer = omnivore.kml(kmlUrl, null, customLayer)
                    .on('ready', function() {
                        map.fitBounds(runLayer.getBounds());
                        
                        var featureList = document.getElementById('feature-list');
                        var container = document.getElementById('kml-list-container');
                        
                        runLayer.eachLayer(function(layer) {
                            if (layer.feature && layer.feature.properties && layer.feature.properties.name) {
                                container.style.display = 'block'; // show container if there are features
                                var name = layer.feature.properties.name;
                                
                                var listItem = document.createElement('a');
                                listItem.href = 'javascript:void(0)';
                                listItem.className = 'list-group-item list-group-item-action d-flex align-items-center';
                                
                                var geomType = layer.feature.geometry.type;
                                var icon = 'bi-geo-alt-fill text-danger';
                                if (geomType === 'LineString' || geomType === 'MultiLineString') {
                                    icon = 'bi-cursor-fill text-primary';
                                }
                                
                                listItem.innerHTML = '<i class="bi ' + icon + ' me-3 fs-5"></i><span>' + name + '</span>';
                                
                                listItem.addEventListener('click', function() {
                                    if (layer.getBounds) {
                                        map.fitBounds(layer.getBounds());
                                    } else if (layer.getLatLng) {
                                        map.flyTo(layer.getLatLng(), 16);
                                    }
                                    if(layer.openPopup) {
                                        layer.openPopup();
                                    }
                                });
                                
                                featureList.appendChild(listItem);
                            }
                        });
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
