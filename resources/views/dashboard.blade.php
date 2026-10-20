<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    {{ __('Rekap Pesanan Sewa Masuk') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Pantau dan kelola seluruh transaksi penyewaan alat outdoor.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('gears.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-sm transition">
                    🏕️ Kelola Data Gears
                </a>
                <a href="{{ route('customer.index') }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 shadow-sm transition">
                    👁️ Lihat Web Customer
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi Sukses --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg shadow-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Notifikasi Error --}}
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg shadow-sm font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b">
                            <tr>
                                <th class="p-4">Kode Booking</th>
                                <th class="p-4">Pelanggan</th>
                                <th class="p-4">Jadwal Sewa</th>
                                <th class="p-4">Rincian Barang</th>
                                <th class="p-4">Total Biaya</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-center">Ubah Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y text-sm">
                            @forelse($orders as $order)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-4 font-mono font-bold text-blue-600">
                                        {{ $order->booking_code }}
                                    </td>
                                    <td class="p-4">
                                        <div class="font-bold text-gray-800">{{ $order->customer_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->customer_email }}</div>
                                        <div class="text-xs text-gray-500 font-semibold mt-0.5">📱 {{ $order->customer_phone }}</div>
                                    </td>
                                    <td class="p-4 text-xs text-gray-600">
                                        <div><span class="font-semibold">Mulai:</span> {{ \Carbon\Carbon::parse($order->rental_date)->format('d M Y') }}</div>
                                        <div><span class="font-semibold">Durasi:</span> {{ $order->duration_days }} Hari</div>
                                    </td>
                                    <td class="p-4">
                                        <ul class="space-y-1 text-xs text-gray-700">
                                            @foreach($order->orderDetails as $detail)
                                                <li class="flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                    <span><strong>{{ $detail->gear->name ?? 'Gear Terhapus' }}</strong> &times; {{ $detail->quantity }} unit</span>
                                                    <span class="text-gray-400">({{ number_format($detail->subtotal) }})</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="p-4 font-extrabold text-green-600 whitespace-nowrap">
                                        Rp {{ number_format($order->total_price) }}
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        @if($order->status == 'Pending')
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2.5 py-1 rounded-full">PENDING</span>
                                        @elseif($order->status == 'Confirmed')
                                            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-1 rounded-full">CONFIRMED</span>
                                        @elseif($order->status == 'Completed')
                                            <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-1 rounded-full">COMPLETED</span>
                                        @elseif($order->status == 'Cancelled')
                                            <span class="bg-red-100 text-red-800 text-xs font-bold px-2.5 py-1 rounded-full">CANCELLED</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2.5 py-1 rounded-full">{{ strtoupper($order->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center whitespace-nowrap">
                                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()" class="text-xs border border-gray-300 rounded-lg p-1.5 bg-white shadow-sm font-semibold focus:ring-2 focus:ring-blue-500">
                                                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="Confirmed" {{ $order->status == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                                <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-gray-400 font-medium">
                                        Belum ada data pesanan sewa yang masuk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
