<li data-id="{{ $menu->id }}" class="menu-item bg-white border border-gray-200 rounded-md mb-2 shadow-sm">
    <div class="flex items-center justify-between p-4">
        <div class="flex items-center gap-4">
            <span class="drag-handle cursor-grab text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grip-vertical h-6 w-6" viewBox="0 0 16 16">
                  <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                </svg>
            </span>
            <div>
                <span class="font-semibold text-gray-800">{{ $menu->name }}</span>
                <span class="text-sm text-gray-500 ml-2">{{ $menu->url }}</span>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm {{ $menu->is_active ? 'text-green-600' : 'text-gray-400' }}">
                {{ $menu->is_active ? 'Active' : 'Inactive' }}
            </span>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.menu.edit', $menu) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</a>
                <form action="{{ route('admin.menu.destroy', $menu) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>
    
    <ul class="nested-sortable pl-8 pb-2 pr-2 min-h-[10px]" data-parent-id="{{ $menu->id }}">
        @if($menu->children->count())
            @foreach($menu->children as $child)
                @include('admin.menu.partials.menu-item', ['menu' => $child])
            @endforeach
        @endif
    </ul>
</li>
