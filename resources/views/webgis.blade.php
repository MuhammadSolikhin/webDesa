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
        width: 360px;
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
            height: 320px;
            order: 2; /* Move sidebar below map on mobile */
        }
        .webgis-map-wrapper {
            order: 1;
            height: calc(100vh - 390px);
        }
    }

    .layer-item {
        transition: all 0.2s ease;
        border-radius: 8px;
        padding: 8px 10px;
    }
    .layer-item:hover {
        background-color: #eef2f5;
    }
    .color-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }
</style>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<div class="webgis-container">
    <div class="webgis-sidebar">
        <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm mb-4"><i class="bi bi-arrow-left"></i> Kembali</a>
        <h3 class="fw-bold mb-1" style="color: var(--theme-green, #14532d);">WebGIS Interaktif</h3>
        <p class="text-muted small mb-4"><i class="bi bi-geo-alt"></i> {{ $settings['contact_address'] ?? 'Kawasan Transmigrasi' }}</p>
        
        <p class="small text-muted mb-3">
            Jelajahi hasil pemetaan spasial dan data geospasial. Centang layer untuk menampilkan di peta, dan klik objek mana pun untuk melihat informasi atribut.
        </p>

        <div class="card border-0 bg-success text-white mb-4 p-3 rounded-4 shadow-sm" style="background-color: var(--theme-green-overlay, #1b5e20) !important;">
            <div class="d-flex align-items-center">
                <i class="bi bi-layers fs-3 me-3"></i>
                <div>
                    <strong class="d-block">Mendukung Multi-Format</strong>
                    <span class="small opacity-75">GeoJSON, KML, dan Shapefile (dari RAR / ZIP)</span>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold text-uppercase text-muted m-0" style="font-size: 0.8rem; letter-spacing: 0.5px;">Daftar Layer Spasial</h6>
            <span class="badge bg-secondary rounded-pill">{{ $layers->count() }}</span>
        </div>

        <div class="list-group list-group-flush" id="layer-list">
            @php
                $palette = ['#16a34a', '#2563eb', '#d97706', '#dc2626', '#9333ea', '#0891b2', '#ea580c', '#4f46e5'];
            @endphp
            @foreach($layers as $index => $layer)
                @php
                    $color = $palette[$index % count($palette)];
                @endphp
                <div class="layer-item d-flex align-items-center justify-content-between mb-1">
                    <div class="d-flex align-items-center flex-grow-1 overflow-hidden me-2">
                        <input class="form-check-input me-2.5 layer-toggle shadow-sm" type="checkbox" value="{{ $layer->id }}" checked 
                            data-url="{{ Storage::url($layer->file) }}" 
                            data-name="{{ $layer->name }}" 
                            data-ext="{{ $layer->extension }}"
                            data-color="{{ $color }}"
                            style="cursor: pointer;">
                        <span class="color-dot" style="background-color: {{ $color }};"></span>
                        <div class="flex-grow-1 overflow-hidden">
                            <span class="d-block text-truncate fw-medium" style="cursor: pointer; font-size: 0.9rem;" title="{{ $layer->name }}">{{ $layer->name }}</span>
                            <span class="badge bg-light text-secondary border px-1.5 py-0.5" style="font-size: 0.65rem;">{{ $layer->type_label }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        @if($layer->description)
                            <i class="bi bi-info-circle text-muted" title="{{ $layer->description }}" data-bs-toggle="tooltip"></i>
                        @endif
                        <a href="{{ Storage::url($layer->file) }}" download class="btn btn-link text-muted p-1" title="Unduh File Spasial" style="font-size: 0.85rem;">
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                </div>
            @endforeach
            @if($layers->count() == 0)
                <div class="text-center py-4">
                    <i class="bi bi-geo-alt text-muted fs-1 d-block mb-2"></i>
                    <p class="text-muted small">Belum ada layer WebGIS yang diunggah oleh Admin.</p>
                </div>
            @endif
        </div>
    </div>
    
    <div class="webgis-map-wrapper">
        <div id="map"></div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<!-- Leaflet Omnivore for KML -->
<script src="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.3.1/leaflet-omnivore.min.js"></script>
<!-- shpjs for Shapefile / ZIP -->
<script src="https://unpkg.com/shpjs@latest/dist/shp.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi tooltip Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Inisialisasi peta Leaflet
    var map = L.map('map').setView([-3.445, 140.769], 9); // Koordinat default

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

    // Wadah layer dan batas peta
    var geojsonLayers = {};
    var bounds = L.latLngBounds();

    // Helper untuk membuat popup atribut
    function createPopupContent(name, properties) {
        var content = '<div style="min-width: 220px; max-width: 320px;">' +
                      '<h6 style="color: #1b5e20; border-bottom: 1px solid #e0e0e0; padding-bottom: 5px; margin-bottom: 8px;"><b>' + name + '</b></h6>';
        if (properties && Object.keys(properties).length > 0) {
            content += '<div style="max-height: 200px; overflow-y: auto;">' +
                       '<table class="table table-sm table-borderless m-0" style="font-size: 0.8rem;">';
            for (var key in properties) {
                if (properties[key] !== null && properties[key] !== '') {
                    content += '<tr><th class="text-muted" style="width: 40%; vertical-align: top;">' + key + '</th><td>' + properties[key] + '</td></tr>';
                }
            }
            content += '</table></div>';
        } else {
            content += '<span class="text-muted" style="font-size: 0.8rem;">Tidak ada data atribut.</span>';
        }
        content += '</div>';
        return content;
    }

    // Helper render GeoJSON seragam (untuk GeoJSON asli maupun hasil konversi Shapefile)
    function renderGeoJsonData(id, data, name, color) {
        var layer = L.geoJSON(data, {
            onEachFeature: function(feature, featureLayer) {
                if (feature.properties) {
                    featureLayer.bindPopup(createPopupContent(name, feature.properties));
                }
            },
            style: function(feature) {
                return {
                    color: color,
                    weight: 2.5,
                    opacity: 0.9,
                    fillColor: color,
                    fillOpacity: 0.35
                };
            },
            pointToLayer: function(feature, latlng) {
                return L.circleMarker(latlng, {
                    radius: 7,
                    fillColor: color,
                    color: "#ffffff",
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.85
                });
            }
        }).addTo(map);

        geojsonLayers[id] = layer;
        if (layer.getBounds && layer.getBounds().isValid()) {
            bounds.extend(layer.getBounds());
            map.fitBounds(bounds, { padding: [50, 50] });
        }
    }

    // Fungsi pemuat layer berdasarkan ekstensi file
    function loadLayer(id, url, name, ext, color) {
        if (!url) return;
        ext = (ext || url.split('.').pop() || '').toLowerCase();

        // 1. Format GeoJSON / JSON
        if (ext === 'geojson' || ext === 'json') {
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('HTTP ' + response.status);
                    return response.json();
                })
                .then(data => {
                    renderGeoJsonData(id, data, name, color);
                })
                .catch(err => {
                    console.error('Error memuat GeoJSON (' + name + '):', err);
                });
        }
        // 2. Format KML (menggunakan Leaflet Omnivore)
        else if (ext === 'kml') {
            var customLayer = L.geoJson(null, {
                onEachFeature: function(feature, featureLayer) {
                    var title = (feature.properties && (feature.properties.name || feature.properties.title)) || name;
                    var desc = feature.properties ? feature.properties.description : '';
                    var popup = '<div><h6><b>' + title + '</b></h6>';
                    if (desc) popup += '<div style="font-size:0.8rem; max-height:150px; overflow-y:auto;">' + desc + '</div>';
                    popup += '</div>';
                    featureLayer.bindPopup(popup);
                },
                style: function() {
                    return { color: color, weight: 2.5, opacity: 0.9, fillOpacity: 0.35 };
                }
            });

            var kmlLayer = omnivore.kml(url, null, customLayer)
                .on('ready', function() {
                    geojsonLayers[id] = kmlLayer;
                    if (kmlLayer.getBounds && kmlLayer.getBounds().isValid()) {
                        bounds.extend(kmlLayer.getBounds());
                        map.fitBounds(bounds, { padding: [50, 50] });
                    }
                })
                .on('error', function(err) {
                    console.error('Error memuat KML (' + name + '):', err);
                })
                .addTo(map);
        }
        // 3. Format Shapefile / ZIP (menggunakan shpjs)
        else if (ext === 'zip' || ext === 'shp') {
            if (typeof shp !== 'undefined') {
                shp(url)
                    .then(function(geojson) {
                        renderGeoJsonData(id, geojson, name, color);
                    })
                    .catch(function(err) {
                        console.error('Error memuat Shapefile (' + name + '):', err);
                    });
            } else {
                console.warn('Library shpjs belum siap untuk memuat Shapefile.');
            }
        }
    }

    // Inisialisasi checkbox toggle untuk setiap layer
    var toggles = document.querySelectorAll('.layer-toggle');
    toggles.forEach(function(toggle) {
        var id = toggle.value;
        var url = toggle.getAttribute('data-url');
        var name = toggle.getAttribute('data-name');
        var ext = toggle.getAttribute('data-ext');
        var color = toggle.getAttribute('data-color') || '#16a34a';

        if (toggle.checked) {
            loadLayer(id, url, name, ext, color);
        }

        toggle.addEventListener('change', function() {
            if (this.checked) {
                if (!geojsonLayers[id]) {
                    loadLayer(id, url, name, ext, color);
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
