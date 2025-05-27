<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Promo;


class CartController extends Controller
{
    // Tampilkan isi cart
    public function index()
    {
        // Ambil seluruh cart dari session
        $cart = session('cart', []);

        return view('cart.index', compact('cart'));
    }

public function add($id)
{
    $product = Product::find($id);

    if (!$product) {
        $product = Promo::find($id);
        if (!$product) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }

        // Jika promo, pakai harga diskon
        $price = $product->original_price;
        if ($product->discount_percentage) {
            $price = floor($product->original_price * (1 - $product->discount_percentage / 100));
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += 1;
        } else {
            $cart[$id] = [
                'name' => $product->title,
                'price' => $price,
                'quantity' => 1,
            ];
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Promo berhasil ditambahkan ke keranjang!');
    }

    // Produk biasa
    if ($product->stock <= 0) {
        return back()->with('error', 'Produk habis stok!');
    }

    $cart = session()->get('cart', []);

    if (isset($cart[$product->id])) {
        $cart[$product->id]['quantity'] += 1;
    } else {
        $cart[$product->id] = [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
        ];
    }

    session(['cart' => $cart]);

    return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
}

    // Tambah produk ke cart dari form (pakai product_id)
    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $product = Product::find($productId);

        if (!$product || $product->stock <= 0) {
            return back()->with('error', 'Produk tidak ditemukan atau habis stok!');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += 1;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
            ];
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // Hapus produk dari cart
public function remove($id)
{
    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        unset($cart[$id]);
        session(['cart' => $cart]);
    }

    return back()->with('success', 'Item berhasil dihapus dari keranjang!');
}

    // Halaman checkout
    public function checkoutPage()
    {
        $cart = session('cart', []);
        return view('checkout', compact('cart'));
    }

    // Proses checkout
    public function checkout(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong, tidak bisa checkout.');
        }

        // TODO: Simpan transaksi ke DB dan kirim notif ke admin

        // Kosongkan cart setelah checkout
        session()->forget('cart');

        return redirect()->route('welcome')->with('success', 'Transaksi berhasil! Terima kasih sudah berbelanja.');
    }
}
