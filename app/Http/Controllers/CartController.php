<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Promo;
use App\Models\NewProduct;


class CartController extends Controller
{
    // Tampilkan isi cart
    public function index()
    {
        $cart = session('cart', []);
        return view('cart.index', compact('cart'));
    }

    // Tambah produk atau promo ke cart berdasarkan ID
public function add($id)
{
    $cart = session()->get('cart', []);

    // Cek produk biasa
    $product = Product::find($id);
    if ($product) {
        if ($product->stock <= 0) {
            return back()->with('error', 'Produk habis stok!');
        }
        if (isset($cart['product-'.$id])) {
            $cart['product-'.$id]['quantity'] += 1;
        } else {
            $cart['product-'.$id] = [
                'type' => 'product',
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'photo' => $product->photo ?? null,
            ];
        }
        session(['cart' => $cart]);
        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // Cek produk baru
    $newProduct = \App\Models\NewProduct::find($id);
    if ($newProduct) {
        if ($newProduct->stock <= 0) {
            return back()->with('error', 'Produk baru habis stok!');
        }
        if (isset($cart['newproduct-'.$id])) {
            $cart['newproduct-'.$id]['quantity'] += 1;
        } else {
            $cart['newproduct-'.$id] = [
                'type' => 'newproduct',
                'name' => $newProduct->title,
                'price' => $newProduct->price,
                'quantity' => 1,
                'photo' => $newProduct->image ?? null,
            ];
        }
        session(['cart' => $cart]);
        return back()->with('success', 'Produk baru berhasil ditambahkan ke keranjang!');
    }

    // Kalau gak ada produk biasa dan baru, cek promo
    $promo = Promo::find($id);
    if (!$promo) {
        return back()->with('error', 'Produk tidak ditemukan.');
    }
    $price = $promo->original_price;
    if ($promo->discount_percentage) {
        $price = floor($promo->original_price * (1 - $promo->discount_percentage / 100));
    }
    if (isset($cart['promo-'.$id])) {
        $cart['promo-'.$id]['quantity'] += 1;
    } else {
        $cart['promo-'.$id] = [
            'type' => 'promo',
            'name' => $promo->title,
            'price' => $price,
            'quantity' => 1,
            'photo' => $promo->photo ?? null,
        ];
    }
    session(['cart' => $cart]);
    return back()->with('success', 'Promo berhasil ditambahkan ke keranjang!');
}

    // Hapus item dari cart berdasarkan ID
    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session(['cart' => $cart]);
            return back()->with('success', 'Item berhasil dihapus dari keranjang!');
        }
        return back()->with('error', 'Item tidak ditemukan di keranjang.');
    }
}
