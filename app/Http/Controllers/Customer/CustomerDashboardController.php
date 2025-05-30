<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Custom;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function __construct()
{
    $this->middleware('auth');
}

    public function dashboard(Request $request)
    {
        $customRequests = Custom::where('customer_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        $latestCustom = null;
        if ($request->session()->has('latest_id')) {
            $latestId = $request->session()->get('latest_id');
            $latestCustom = Custom::where('customer_id', auth()->id())->find($latestId);
        }

        if (!$latestCustom) {
            $latestCustom = Custom::where('customer_id', auth()->id())
                ->where('status', 'disetujui')
                ->latest('updated_at')
                ->first();
        }

        return view('customer.dashboard', compact('customRequests', 'latestCustom'));
    }
}