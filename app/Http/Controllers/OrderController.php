<?php

namespace App\Http\Controllers;

use App\Models\Gear;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Menampilkan katalog perlengkapan outdoor pada halaman pemesanan pelanggan.
     */
    public function index()
    {
        $gears = Gear::all();
        return view('customer.index', compact('gears'));
    }

    /**
     * Memvalidasi dan menyimpan transaksi pemesanan sewa beserta rincian itemnya.
     */
    public function store(Request $request)
    {
        // 1. Validasi input formulir
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
            'rental_date'    => 'required|date|after_or_equal:today',
            'duration_days'  => 'required|integer|min:1',
            'items'          => 'required|array',
            'items.*'        => 'required|integer|min:0',
        ]);

        // 2. Filter item yang kuantitas sewanya lebih dari 0
        $orderedItems = array_filter($request->items, fn ($qty) => $qty > 0);

        if (empty($orderedItems)) {
            return back()->with('error', 'Silakan pilih minimal 1 perlengkapan untuk disewa.');
        }

        // 3. Gunakan Database Transaction agar data tersimpan secara utuh
        DB::beginTransaction();

        try {
            // Generate kode booking unik (contoh: CAMP-20261020-8392)
            $bookingCode = 'CAMP-' . date('Ymd') . '-' . rand(1000, 9999);

            // Simpan data induk pesanan
            $order = Order::create([
                'booking_code'   => $bookingCode,
                'customer_name'  => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->customer_email,
                'rental_date'    => $request->rental_date,
                'duration_days'  => $request->duration_days,
                'total_price'    => 0, // Akan dihitung dan diperbarui setelah rincian dihitung
                'status'         => 'Pending',
            ]);

            $totalPrice = 0;

            // Simpan setiap rincian barang sewa ke tabel order_details
            foreach ($orderedItems as $gearId => $qty) {
                $gear = Gear::findOrFail($gearId);

                // Subtotal = harga sewa harian * jumlah unit * durasi hari sewa
                $subtotal = $gear->rental_price * $qty * $request->duration_days;
                $totalPrice += $subtotal;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'gear_id'  => $gear->id,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ]);
            }

            // Perbarui total tagihan pada pesanan utama
            $order->update(['total_price' => $totalPrice]);

            DB::commit();

            return redirect()->route('customer.index')->with('success', 'Pesanan sewa berhasil dibuat! Kode Booking Anda: ' . $bookingCode);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan seluruh daftar pesanan pada dashboard admin.
     */
    public function adminDashboard()
    {
        // Mengambil semua pesanan beserta rincian alatnya secara eager loading
        $orders = Order::with('orderDetails.gear')->latest()->get();
        return view('dashboard', compact('orders'));
    }

    /**
     * Memvalidasi dan memperbarui status pesanan oleh admin.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Confirmed,Completed,Cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}
