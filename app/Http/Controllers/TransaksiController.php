<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // Tampilkan semua transaksi dengan relasi user dan item produk
    public function index()
    {
        $transaksi = Transaksi::with('user', 'transaksiItems.product')->get();
        return view('transaksi.index', compact('transaksi'));
    }

    // Tampilkan detail transaksi
    public function show($id)
    {
        $transaksi = Transaksi::with('user', 'transaksiItems.product')->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }

    // Simpan transaksi baru sekaligus kurangi stok produk
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|string',
            'status' => 'required|string|in:pending,completed,cancelled',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'payment_method' => 'required|string',
            'shipping_method' => 'required|string',
        ]);

        $items = json_decode($request->items, true);

        if (!is_array($items) || count($items) == 0) {
            return back()->withErrors('Data item tidak valid atau kosong.');
        }

        DB::beginTransaction();
        try {
            $transaksi = new Transaksi();
            $transaksi->user_id = $request->user_id;
            $transaksi->status = $request->status;
            $transaksi->no_hp = $request->no_hp;
            $transaksi->alamat = $request->alamat;
            $transaksi->payment_method = $request->payment_method;
            $transaksi->shipping_method = $request->shipping_method;
            $transaksi->total_harga = 0;
            $transaksi->save();

            $totalHarga = 0;

            foreach ($items as $itemData) {
                if (!isset($itemData['id'], $itemData['quantity'], $itemData['price'])) {
                    DB::rollBack();
                    return back()->withErrors('Data item tidak lengkap.');
                }

                $product = Product::findOrFail($itemData['id']);

                if ($product->stock < $itemData['quantity']) {
                    DB::rollBack();
                    return back()->withErrors("Stok produk '{$product->nama}' tidak cukup.");
                }

                // Kurangi stok produk
                $product->stock -= $itemData['quantity'];
                $product->save();

                $item = new TransaksiItem();
                $item->transaksi_id = $transaksi->id;
                $item->product_id = $product->id;
                $item->jumlah = $itemData['quantity'];
                $item->total_harga = $product->harga * $itemData['quantity'];
                $item->save();

                $totalHarga += $item->total_harga;
            }

            $transaksi->total_harga = $totalHarga;
            $transaksi->save();

            DB::commit();

            return redirect()->route('transaksi.show', $transaksi->id)
                             ->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Update status transaksi
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->status = $request->status;
        $transaksi->save();

        return back()->with('success', 'Status pesanan berhasil diupdate.');
    }
}
