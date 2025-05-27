<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // Halaman checkout, menerima input produk terpilih & kuantitas
    public function checkoutPage(Request $request)
    {
        $cart = session('cart', []); // ambil cart dari session, default []
        $selected = $request->input('selected_products', []); // array id produk yg dipilih
        $quantities = $request->input('quantities', []); // array quantity per product id

        $checkoutItems = [];

        foreach ($selected as $productId) {
            if (isset($cart[$productId])) {
                $checkoutItems[] = [
                    'product_id' => $productId,
                    'name'       => $cart[$productId]['name'],
                    'price'      => $cart[$productId]['price'],
                    'quantity'   => isset($quantities[$productId]) ? intval($quantities[$productId]) : $cart[$productId]['quantity'],
                ];
            }
        }

        return view('checkout', compact('checkoutItems'));
    }

    // Proses konfirmasi checkout dan simpan transaksi & itemnya
    public function confirm(Request $request)
    {
        $request->validate([
            'items'          => 'required|string',
            'payment_method' => 'required|string',
            'shipping_method'=> 'required|string',
            'no_hp'          => 'required|string|max:20',
            'alamat'         => 'required|string|max:255',
        ]);

        // Pastikan user sudah login
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login')->withErrors('Silakan login terlebih dahulu.');
        }

        // Decode items JSON ke array
        $items = json_decode($request->input('items'), true);

        if (!is_array($items) || count($items) === 0) {
            return back()->withErrors('Data item tidak valid.');
        }

        // Validasi tiap item: harus ada product_id, price, quantity dan harus numeric
        foreach ($items as $index => $item) {
            if (!isset($item['product_id'], $item['price'], $item['quantity'])) {
                return back()->withErrors("Data item ke-$index tidak lengkap.");
            }
            if (!is_numeric($item['price']) || !is_numeric($item['quantity'])) {
                return back()->withErrors("Data item ke-$index harus berupa angka.");
            }
        }

        DB::beginTransaction();

        try {
            $totalHarga = 0;

            // Buat transaksi baru dulu
            $transaksi = Transaksi::create([
                'user_id'         => $userId,
                'status'          => 'pending',
                'no_hp'           => $request->no_hp,
                'alamat'          => $request->alamat,
                'payment_method'  => $request->payment_method,
                'shipping_method' => $request->shipping_method,
                'total_harga'     => 0, // nanti diupdate
            ]);

            // Simpan tiap item transaksi
            foreach ($items as $item) {
                TransaksiItem::create([
                    'transaksi_id' => $transaksi->id,
                    'product_id'   => $item['product_id'],
                    'quantity'     => intval($item['quantity']),
                    'price'        => floatval($item['price']),
                ]);

                $totalHarga += $item['price'] * $item['quantity'];
            }

            // Update total harga transaksi
            $transaksi->update(['total_harga' => $totalHarga]);

            DB::commit();

            // Bersihkan cart session
            session()->forget('cart');

            return redirect()->route('welcome')->with('success', 'Checkout berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Terjadi kesalahan saat memproses transaksi: ' . $e->getMessage());
        }
    }
}
