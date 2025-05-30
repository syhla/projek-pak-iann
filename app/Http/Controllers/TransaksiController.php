<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    /**
     * Hanya untuk admin: Tampilkan daftar semua transaksi.
     */
    public function index()
    {
        $this->authorize('viewAny', Transaksi::class); // Optional jika pakai policy

        $transaksis = Transaksi::with('user', 'transaksiItems.product')
            ->latest()
            ->paginate(10);

        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Untuk customer: Tampilkan detail transaksi miliknya sendiri.
     */
    public function show($id)
    {
        $transaksi = Transaksi::with(['transaksiItems.product', 'user'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('customer.transactions.show', compact('transaksi'));
    }

    /**
     * Simpan transaksi baru dari customer.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'status' => 'required|string|in:pending,completed,cancelled',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'payment_method' => 'required|string',
            'shipping_method' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            // Buat transaksi utama
            $transaksi = Transaksi::create([
                'user_id' => $request->user_id,
                'status' => $request->status,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'payment_method' => $request->payment_method,
                'shipping_method' => $request->shipping_method,
                'total_harga' => 0,
            ]);

            $totalHarga = 0;

            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['id']);

                if ($product->stock < $itemData['quantity']) {
                    DB::rollBack();
                    return back()->withErrors("Stok produk '{$product->nama}' tidak cukup.");
                }

                $product->stock -= $itemData['quantity'];
                $product->save();

                $subtotal = $product->harga * $itemData['quantity'];

                TransaksiItem::create([
                    'transaksi_id' => $transaksi->id,
                    'product_id' => $product->id,
                    'jumlah' => $itemData['quantity'],
                    'total_harga' => $subtotal,
                ]);

                $totalHarga += $subtotal;
            }

            $transaksi->update(['total_harga' => $totalHarga]);

            DB::commit();

            return redirect()->route('transactions.show', $transaksi->id)
                             ->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hanya untuk admin: Update status transaksi.
     */
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

    /**
     * Hanya untuk admin: Hapus transaksi dan kembalikan stok produk.
     */
    public function destroy($id)
    {
        $transaksi = Transaksi::with('transaksiItems.product')->findOrFail($id);

        DB::beginTransaction();

        try {
            foreach ($transaksi->transaksiItems as $item) {
                if ($item->product) {
                    $item->product->stock += $item->jumlah;
                    $item->product->save();
                }
            }

            $transaksi->transaksiItems()->delete();
            $transaksi->delete();

            // Redirect sesuai role
            if (Auth::user()->hasRole('admin')) {
                return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil dihapus.');
            } else {
                return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
}
