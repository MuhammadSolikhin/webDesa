@extends('layouts.landing')

@section('content')
<style>
    /* Full height map layout */
    .webgis-container {
        display: flex;
        height: calc(100vh - 90px); /* Adjust based on navbar height */
        margin-top: 90px;
    }
    .webgis-sidebar {
        width: 350px;
        background: #f8f9fa;
        padding: 20px;
        overflow-y: auto;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        z-index: 1000; /* above map */
    }
    .webgis-map-wrapper {
        flex-grow: 1;
        position: relative;
    }
    #map {
        width: 100%;
        height: 100%;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .webgis-container {
            flex-direction: column;
            height: calc(100vh - 70px);
            margin-top: 70px;
        }
        .webgis-sidebar {
            width: 100%;
            height: 300px;
            order: 2; /* Move sidebar below map on mobile */
        }
        .webgis-map-wrapper {
            order: 1;
            height: calc(100vh - 370px);
        }
    }
</style>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<div class="webgis-container">
    <div class="webgis-sidebar">
        <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm mb-4"><i class="bi bi-arrow-left"></i> Kembali</a>
        <h3 class="fw-bold mb-1" style="color: var(--theme-green, #14532d);">WebGIS Interaktif</h3>
        <p class="text-muted small mb-4"><i class="bi bi-geo-alt"></i> {{ $settings['contact_address'] ?? 'Kawasan Transmigrasi' }}</p>
        
        <p class="small text-muted mb-4">
            Jelajahi hasil pemetaan spasial dan titik lokasi. Klik objek mana pun di peta untuk melihat detail atribut dan sumber data.
        </p>

        <div class="card border-0 bg-success text-white mb-4 p-3 rounded-4 shadow-sm" style="background-color: var(--theme-green-overlay) !important;">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle fs-3 me-3"></i>
                <div>
                    <strong class="d-block">Klik objek pada peta</strong>
                    <span class="small opacity-75">Lihat atribut, kelompok data, dan geometri objek</span>
                </div>
            </div>
        </div>

        <h6 class="fw-bold text-uppercase text-muted mb-3">Daftar Layer</h6>
        <div class="list-group list-group-flush" id="layer-list">
            @foreach($layers as $layer)
                <label class="list-group-item d-flex align-items-center bg-transparent px-0 border-bottom-0">
                    <input class="form-check-input me-3 layer-toggle shadow-sm" type="checkbox" value="{{ $layer->id }}" checked data-url="{{ Storage::url($layer->file) }}" data-name="{{ $layer->name }}" style="cursor: pointer;">
                    <span class="flex-grow-1" style="cursor: pointer;">{{ $layer->name }}</span>
                    @if($layer->description)
                        <i class="bi bi-info-circle text-muted ms-2" title="{{ $layer->description }}" data-bs-toggle="tooltip"></i>
                    @endif
                </label>
            @endforeach
            @if($layers->count() == 0)
                <p class="text-muted small">Belum ada layer WebGIS yang diunggah oleh Admin.</p>
            @endif
        </div>
    </div>
    
    <div class="webgis-map-wrapper">
        <div id="map"></div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Initialize map
    var map = L.map('map').setView([-3.445, 140.769], 9); // Fallback coordinates

    // Base layers
    var streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri'
    });

    var topoMap = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data: &copy; OSM'
    });

    var baseMaps = {
        "Jalan (Street)": streetMap,
        "Satelit": satelliteMap,
        "Topografi": topoMap
    };

    L.control.layers(baseMaps).addTo(map);

    // Store loaded geojson layers
    var geojsonLayers = {};
    var bounds = L.latLngBounds();

    // Function to load and add a layer
    function loadLayer(id, url, name) {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                var layer = L.geoJSON(data, {
                    onEachFeature: function (feature, layer) {
                        var popupContent = '<div style="min-width: 200px;"><h6 style="color: var(--theme-green-overlay); border-bottom: 1px solid #eee; padding-bottom: 5px; margin-bottom: 10px;"><b>' + name + '</b></h6>';
                        if (feature.properties) {
                            popupContent += '<table class="table table-sm table-borderless m-0">';
                            for (var key in feature.properties) {
                                // Skip generic or overly long properties if needed, but display all by default
                                if (feature.properties[key] !== null && feature.properties[key] !== '') {
                                    popupContent += '<tr><th style="font-size: 0.85rem; width: 40%;" class="text-muted">' + key + '</th><td style="font-size: 0.85rem;">' + feature.properties[key] + '</td></tr>';
                                }
                            }
                            popupContent += '</table>';
                        }
                        popupContent += '</div>';
                        layer.bindPopup(popupContent);
                    },
                    style: function(feature) {
                        return { color: "#14532d", weight: 2, opacity: 0.8, fillOpacity: 0.3 };
                    }
                }).addTo(map);
                
                geojsonLayers[id] = layer;
                bounds.extend(layer.getBounds());
                
                // Fit bounds if they are valid
                if (bounds.isValid()) {
                    map.fitBounds(bounds, { padding: [50, 50] });
                }
            })
            .catch(error => {
                console.error('Error loading GeoJSON:', error);
                // Try KML if GeoJSON parsing fails? For now, we only support GeoJSON natively in JS this way.
            });
    }

    // Initialize checkboxes
    var toggles = document.querySelectorAll('.layer-toggle');
    toggles.forEach(function(toggle) {
        var id = toggle.value;
        var url = toggle.getAttribute('data-url');
        var name = toggle.getAttribute('data-name');

        if (toggle.checked) {
            loadLayer(id, url, name);
        }

        toggle.addEventListener('change', function() {
            if (this.checked) {
                if (!geojsonLayers[id]) {
                    loadLayer(id, url, name);
                } else {
                    map.addLayer(geojsonLayers[id]);
                }
            } else {
                if (geojsonLayers[id]) {
                    map.removeLayer(geojsonLayers[id]);
                }
            }
        });
    });
});
</script>
@endsection
