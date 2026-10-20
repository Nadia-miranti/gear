<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Perlengkapan</h2>
    </x-slot>

    <div class="py-12 max-w-2xl mx-auto sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('gears.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-sm border">
            @csrf
            <div class="mb-4">
                <label class="block font-medium mb-1">Nama Perlengkapan</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-1">Kategori</label>
                <select name="category" class="w-full border rounded p-2" required>
                    <option value="Tenda" {{ old('category') == 'Tenda' ? 'selected' : '' }}>Tenda</option>
                    <option value="Alat Masak" {{ old('category') == 'Alat Masak' ? 'selected' : '' }}>Alat Masak</option>
                    <option value="Penerangan" {{ old('category') == 'Penerangan' ? 'selected' : '' }}>Penerangan</option>
                    <option value="Sleeping Gear" {{ old('category') == 'Sleeping Gear' ? 'selected' : '' }}>Sleeping Gear</option>
                    <option value="Aksesoris" {{ old('category') == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-1">Harga Sewa / Hari (Rp)</label>
                <input type="number" name="rental_price" value="{{ old('rental_price') }}" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-1">Deskripsi</label>
                <textarea name="description" class="w-full border rounded p-2" rows="3" required>{{ old('description') }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-1">Gambar</label>
                <input type="file" name="image" class="w-full border rounded p-2">
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('gears.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded font-semibold transition">Batal</a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded font-semibold transition">Simpan Data</button>
            </div>
        </form>
    </div>
</x-app-layout>
