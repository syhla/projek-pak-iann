<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiItem;

class CheckoutController extends Controller
{
    // Tampil halaman checkout (ambil data dari session cart)
    public function show()
    {
        $cart = session('cart', []);
        $checkoutItems = [];

        foreach ($cart as $productKey => $item) {
            // Parse product_id dari key seperti 'product-2'
            $parts = explode('-', $productKey);
            $id = (int) $parts[1];

            $checkoutItems[] = [
                'product_id' => $id,
                'name'       => $item['name'],
                'price'      => $item['price'],
                'quantity'   => $item['quantity'],
            ];
        }

        return view('checkout', compact('checkoutItems'));
    }

    // Dapetin produk terpilih buat checkout, biasanya dari halaman cart
    public function checkoutPage(Request $request)
    {
        $cart = session('cart', []);
        $selected = $request->input('selected_products', []);
        $quantities = $request->input('quantities', []);

        $checkoutItems = [];

        foreach ($selected as $productKey) {
            if (isset($cart[$productKey])) {
                $parts = explode('-', $productKey);
                $id = (int) $parts[1];

                $checkoutItems[] = [
                    'product_id' => $id,
                    'name'       => $cart[$productKey]['name'],
                    'price'      => $cart[$productKey]['price'],
                    'quantity'   => isset($quantities[$productKey]) ? intval($quantities[$productKey]) : $cart[$productKey]['quantity'],
                ];
            }
        }

        return view('checkout', compact('checkoutItems'));
    }

    // Proses confirm checkout, simpan transaksi, hapus cart
    public function confirm(Request $request)
    {
        $request->validate([
            'no_hp' => 'required',
            'alamat' => 'required',
            'payment_method' => 'required|in:transfer_bank,ovo,gopay,dana,shopeepay',
            'shipping_method' => 'required|in:gosend,grabexpress',
            'items' => 'required',
        ]);

        $items = json_decode($request->items, true);

        // Pastikan product_id integer, parsing kalau masih berbentuk 'product-2'
        foreach ($items as &$item) {
            if (is_string($item['product_id']) && str_contains($item['product_id'], '-')) {
                $parts = explode('-', $item['product_id']);
                $item['product_id'] = (int) $parts[1];
            }
        }
        unset($item);

        // Hitung total harga
        $totalHarga = 0;
        foreach ($items as $item) {
            $totalHarga += $item['price'] * $item['quantity'];
        }

        // Simpan transaksi & itemnya ke DB, termasuk total_harga
        $transaksi = Transaksi::create([
            'user_id' => auth()->id(),
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'payment_method' => $request->payment_method,
            'shipping_method' => $request->shipping_method,
            'total_harga' => $totalHarga,
            'status' => 'pending',
        ]);

        foreach ($items as $item) {
            TransaksiItem::create([
                'transaksi_id' => $transaksi->id,
                'product_id' => $item['product_id'],
                'jumlah' => $item['quantity'],         // sesuaikan nama kolom, kalau di db pakai "jumlah"
                'total_harga' => $item['price'] * $item['quantity'],  // hitung total harga per item
            ]);
        }

        session()->forget('cart');

        return redirect()->route('customer.checkout.form')->with('success', 'Pesanan berhasil dikonfirmasi!');
    }
}
