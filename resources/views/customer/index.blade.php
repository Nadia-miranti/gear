<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampCraft - Penyewaan Alat Camping</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans min-h-screen">

    <!-- NAVBAR ATAS -->
    <header class="bg-white shadow-sm border-b sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('customer.index') }}" class="flex items-center gap-2">
                <span class="text-2xl">🏕️</span>
                <span class="font-extrabold text-xl text-gray-800 tracking-tight">CampCraft <span class="text-blue-600">Outdoor</span></span>
            </a>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-xs bg-gray-800 hover:bg-gray-700 text-white font-semibold px-4 py-2 rounded-lg transition">
                        Panel Admin
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-xs border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold px-4 py-2 rounded-lg transition">
                        Login Admin
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-800 sm:text-4xl">Sewa Alat Camping & Petualangan</h1>
            <p class="text-gray-500 text-sm mt-2 max-w-xl mx-auto">
                Pilih perlengkapan yang Anda butuhkan, tentukan tanggal serta durasi sewa, lalu isi identitas Anda untuk memesan langsung.
            </p>
        </div>

        <!-- NOTIFIKASI SUCCESS / ERROR / VALIDATION -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 shadow-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 shadow-sm font-semibold">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 shadow-sm font-semibold">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="orderForm" action="{{ route('customer.checkout') }}" method="POST">
            @csrf

            <!-- 1. Informasi Pemesan & Durasi -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border mb-8">
                <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">1</span>
                    Informasi Pemesan & Jadwal Sewa
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap *</label>
                        <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required placeholder="Contoh: Budi Santoso" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nomor WhatsApp / HP *</label>
                        <input type="text" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required placeholder="Contoh: 08123456789" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat Email *</label>
                        <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required placeholder="Contoh: budi@gmail.com" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Mulai Sewa *</label>
                        <input type="date" id="rental_date" name="rental_date" value="{{ old('rental_date', date('Y-m-d')) }}" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Durasi Sewa (Hari) *</label>
                        <input type="number" id="duration_days" name="duration_days" min="1" value="{{ old('duration_days', 1) }}" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>
            </div>

            <!-- 2. Pilih Gears -->
            <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">2</span>
                    Pilih Perlengkapan yang Ingin Disewa
                </h2>

                <!-- FILTER KATEGORI -->
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="filterCategory('all', this)" 
                       class="btn-category px-4 py-1.5 rounded-full font-semibold text-xs transition bg-blue-600 text-white shadow-sm">
                        Semua
                    </button>
                    <button type="button" onclick="filterCategory('Tenda', this)" 
                       class="btn-category px-4 py-1.5 rounded-full font-semibold text-xs transition bg-white text-gray-600 hover:bg-gray-100 border">
                        Tenda
                    </button>
                    <button type="button" onclick="filterCategory('Alat Masak', this)" 
                       class="btn-category px-4 py-1.5 rounded-full font-semibold text-xs transition bg-white text-gray-600 hover:bg-gray-100 border">
                        Alat Masak
                    </button>
                    <button type="button" onclick="filterCategory('Penerangan', this)" 
                       class="btn-category px-4 py-1.5 rounded-full font-semibold text-xs transition bg-white text-gray-600 hover:bg-gray-100 border">
                        Penerangan
                    </button>
                    <button type="button" onclick="filterCategory('Sleeping Gear', this)" 
                       class="btn-category px-4 py-1.5 rounded-full font-semibold text-xs transition bg-white text-gray-600 hover:bg-gray-100 border">
                        Sleeping Gear
                    </button>
                    <button type="button" onclick="filterCategory('Aksesoris', this)" 
                       class="btn-category px-4 py-1.5 rounded-full font-semibold text-xs transition bg-white text-gray-600 hover:bg-gray-100 border">
                        Aksesoris
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($gears as $gear)
                    <div class="gear-card bg-white rounded-2xl shadow-sm border overflow-hidden flex flex-col justify-between hover:shadow-md transition" 
                         data-category="{{ $gear->category ?? 'Tenda' }}">
                        <div>
                            @if($gear->image)
                                <img src="{{ asset('storage/' . $gear->image) }}" alt="{{ $gear->name }}" class="w-full h-44 object-cover">
                            @else
                                <div class="bg-gray-200 h-44 flex flex-col items-center justify-center text-gray-400 font-medium">
                                    <span class="text-3xl">🏕️</span>
                                    <span class="text-xs mt-1">Tanpa Gambar</span>
                                </div>
                            @endif

                            <div class="p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs bg-blue-100 text-blue-700 font-semibold px-2.5 py-0.5 rounded-full">
                                        {{ $gear->category }}
                                    </span>
                                    <span class="font-extrabold text-green-600 text-sm">
                                        Rp {{ number_format($gear->rental_price) }} <span class="text-xs font-normal text-gray-500">/hari</span>
                                    </span>
                                </div>
                                <h3 class="font-bold text-gray-800 text-base">{{ $gear->name }}</h3>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $gear->description }}</p>
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50 border-t">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Jumlah Unit yang Disewa</label>
                            <input type="number" name="items[{{ $gear->id }}]" min="0" value="{{ old('items.' . $gear->id, 0) }}" 
                                   class="item-qty w-full border rounded-lg px-3 py-1.5 text-center font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pesan jika kategori kosong -->
            <div id="emptyCategoryMessage" class="hidden bg-white p-8 rounded-2xl border text-center text-gray-500 font-medium mt-6">
                Perlengkapan untuk kategori ini belum tersedia.
            </div>

            <!-- Tombol Submit -->
            <div class="mt-8 text-right">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3 rounded-xl shadow-lg transition">
                    Sewa Sekarang ➔
                </button>
            </div>
        </form>
    </main>

    <!-- SCRIPT JAVASCRIPT -->
    <script>
        function filterCategory(category, element) {
            document.querySelectorAll('.btn-category').forEach(btn => {
                btn.className = "btn-category px-4 py-1.5 rounded-full font-semibold text-xs transition bg-white text-gray-600 hover:bg-gray-100 border";
            });

            element.className = "btn-category px-4 py-1.5 rounded-full font-semibold text-xs transition bg-blue-600 text-white shadow-sm";

            const cards = document.querySelectorAll('.gear-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                if (category === 'all' || cardCategory === category) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            const emptyMsg = document.getElementById('emptyCategoryMessage');
            if (visibleCount === 0) {
                emptyMsg.classList.remove('hidden');
            } else {
                emptyMsg.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
