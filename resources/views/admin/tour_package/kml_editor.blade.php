<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Konten KML - ') }} {{ $tourPackage->name }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        #map-preview {
            height: calc(100vh - 200px);
            min-height: 500px;
            width: 100%;
            border-radius: 8px;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- HAPUS overflow-hidden DI SINI -->
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        <!-- Form Column -->
                        <div class="lg:col-span-7">
                    
                            <div class="mb-4 text-sm text-gray-600">
                                <p>Di bawah ini adalah daftar titik lokasi dan rute yang terdapat di dalam file KML Anda.</p>
                                <p>Anda dapat: 
                                    <ul class="list-disc ml-5 mt-1">
                                        <li>Mengubah urutan (drag icon garis tiga di sebelah kiri)</li>
                                        <li>Mengganti nama lokasi</li>
                                        <li>Menambahkan atau mengubah deskripsi (mendukung HTML)</li>
                                    </ul>
                                </p>
                            </div>

                            <form method="post" action="{{ route('admin.tour-package.kml.update', $tourPackage) }}">
                                @csrf
                                
                                <div id="kml-items-container" class="space-y-4">
                                    @foreach($items as $item)
                                    <div class="kml-item p-4 border border-transparent rounded-md bg-gray-50 flex items-start gap-4 transition-colors hover:border-indigo-300" data-original-index="{{ $item['index'] }}">
                                        <!-- Drag Handle -->
                                        <div class="cursor-move pt-2 text-gray-400 hover:text-gray-600 handle">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16" />
                                            </svg>
                                        </div>
                                        
                                        <div class="flex-grow space-y-3">
                                            <input type="hidden" name="order[]" value="{{ $item['index'] }}">
                                            
                                            <div>
                                                <x-input-label value="{{ __('Nama Lokasi') }}" />
                                                <x-text-input name="names[]" type="text" class="mt-1 block w-full" value="{{ $item['name'] }}" required />
                                            </div>
                                            
                                            <div>
                                                <x-input-label value="{{ __('Deskripsi') }}" />
                                                <textarea name="descriptions[]" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ $item['description'] }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="mt-6 flex items-center justify-end gap-4">
                                    <a href="{{ route('admin.tour-package.edit', $tourPackage) }}" class="text-sm text-gray-600 hover:text-gray-900 underline">{{ __('Batal') }}</a>
                                    <x-primary-button>{{ __('Simpan Perubahan KML') }}</x-primary-button>
                                </div>
                            </form>

                        </div>
                        
                        <!-- Map Preview Column -->
                        <div class="lg:col-span-5 sticky top-6 self-start">
                            <div class="mb-2 font-semibold text-gray-700">Preview Peta</div>
                            <div id="map-preview" class="border shadow-sm z-0"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS & Omnivore -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.3.1/leaflet-omnivore.min.js"></script>
    
    <!-- SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var container = document.getElementById('kml-items-container');
            if (container) {
                new Sortable(container, {
                    handle: '.handle',
                    animation: 150,
                    ghostClass: 'opacity-50'
                });
            }

            // Initialize Map Preview
            var map = L.map('map-preview').setView([-0.789275, 113.921327], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var kmlUrl = "{{ Storage::url($tourPackage->kml_file) }}";
            var customLayer = L.geoJson(null, {
                style: function(feature) {
                    return { color: '#007bff', weight: 3 };
                },
                onEachFeature: function (feature, layer) {
                    if (feature.properties) {
                        var name = feature.properties.name || 'Lokasi';
                        layer.bindPopup('<b>' + name + '</b>');
                    }
                }
            });

            var runLayer = omnivore.kml(kmlUrl, null, customLayer)
                .on('ready', function() {
                    map.fitBounds(runLayer.getBounds());
                    
                    var mapLayers = [];
                    runLayer.eachLayer(function(layer) {
                        mapLayers.push(layer);
                    });
                    
                    document.querySelectorAll('.kml-item').forEach(function(item) {
                        // When user clicks anywhere inside the item
                        item.addEventListener('click', function() {
                            // Highlight border
                            document.querySelectorAll('.kml-item').forEach(i => {
                                i.classList.remove('border-indigo-500', 'bg-indigo-50');
                                i.classList.add('bg-gray-50', 'border-transparent');
                            });
                            this.classList.remove('bg-gray-50', 'border-transparent');
                            this.classList.add('border-indigo-500', 'bg-indigo-50');

                            var idx = parseInt(this.getAttribute('data-original-index'));
                            var layer = mapLayers[idx];
                            if (layer) {
                                if (layer.getBounds) {
                                    map.fitBounds(layer.getBounds(), { maxZoom: 16 });
                                } else if (layer.getLatLng) {
                                    map.flyTo(layer.getLatLng(), 16);
                                }
                                if (layer.openPopup) {
                                    layer.openPopup();
                                }
                            }
                        });
                    });
                })
                .addTo(map);
        });
    </script>
</x-app-layout>