<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Menus') }}
            </h2>
            <a href="{{ route('admin.menu.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Add Menu
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('success'))
                        <div class="mb-4 text-sm font-medium text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div x-data="menuSortable()" x-init="initSortable()">
                        <div x-show="isSaving" class="mb-4 text-sm font-medium text-indigo-600" style="display: none;">
                            Saving order...
                        </div>
                        <div x-show="saveSuccess" class="mb-4 text-sm font-medium text-green-600" style="display: none;">
                            Order saved successfully!
                        </div>

                        <ul class="nested-sortable min-h-[50px]" id="root-menu-list" data-parent-id="">
                            @foreach($menus as $menu)
                                @include('admin.menu.partials.menu-item', ['menu' => $menu])
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Include SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('menuSortable', () => ({
                isSaving: false,
                saveSuccess: false,
                initSortable() {
                    let nestedSortables = document.querySelectorAll('.nested-sortable');
                    
                    for (let i = 0; i < nestedSortables.length; i++) {
                        new Sortable(nestedSortables[i], {
                            group: 'nested',
                            animation: 150,
                            fallbackOnBody: true,
                            swapThreshold: 0.65,
                            handle: '.drag-handle',
                            ghostClass: 'opacity-50',
                            onEnd: (evt) => {
                                this.saveOrder();
                            }
                        });
                    }
                },
                saveOrder() {
                    this.isSaving = true;
                    this.saveSuccess = false;
                    
                    let items = [];
                    let parseList = (listElement, parentId = null) => {
                        let children = listElement.children;
                        let order = 1;
                        for (let i = 0; i < children.length; i++) {
                            let li = children[i];
                            let id = li.getAttribute('data-id');
                            if (id) {
                                items.push({
                                    id: id,
                                    parent_id: parentId,
                                    order: order++
                                });
                                
                                // Parse nested ul
                                let nestedUl = li.querySelector(':scope > ul.nested-sortable');
                                if (nestedUl) {
                                    parseList(nestedUl, id);
                                }
                            }
                        }
                    };

                    parseList(document.getElementById('root-menu-list'));

                    fetch('{{ route('admin.menu.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ items: items })
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.isSaving = false;
                        if(data.success) {
                            this.saveSuccess = true;
                            setTimeout(() => { this.saveSuccess = false; }, 3000);
                        }
                    })
                    .catch(error => {
                        this.isSaving = false;
                        console.error('Error:', error);
                        alert('An error occurred while saving the menu order.');
                    });
                }
            }));
        });
    </script>
    <style>
        .nested-sortable {
            list-style-type: none;
        }
        .nested-sortable .menu-item {
            cursor: default;
        }
        .nested-sortable .drag-handle {
            cursor: grab;
        }
        .nested-sortable .drag-handle:active {
            cursor: grabbing;
        }
    </style>
</x-app-layout>
