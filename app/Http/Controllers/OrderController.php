<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $orders = Order::latest()->paginate(10);
    return view('admin.orders.index', compact('orders'));
}

public function updateStatus(Request $request, Order $order)
{
    $request->validate(['status' => 'required|in:pending,processing,completed,canceled']);
    $order->update(['status' => $request->status]);
    return back()->with('success', 'Status pesanan diupdate');
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
