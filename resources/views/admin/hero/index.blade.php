<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Hero Banner') }}
            </h2>
            <a href="{{ route('admin.hero.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Tambah Hero Banner
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

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($heroes as $hero)
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        @if($hero->image)
                                            <img src="{{ Storage::url($hero->image) }}" alt="{{ $hero->title }}" class="h-16 w-16 object-cover rounded">
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $hero->title }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ Str::limit($hero->description, 50) }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 flex gap-2">
                                        <a href="{{ route('admin.hero.edit', $hero) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <form action="{{ route('admin.hero.destroy', $hero) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
