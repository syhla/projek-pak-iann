<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\CustomOrder;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'harian');

        // Tentukan rentang tanggal filter
        switch ($filter) {
            case 'mingguan':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;

            case 'bulanan':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;

            case 'harian':
            default:
                $startDate = Carbon::now()->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
        }

        // Ambil data transaksi dan custom order sesuai filter
        $transaksis = Transaksi::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $customOrders = CustomOrder::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung total sesuai filter
        $totalTransaksi = $transaksis->count() + $customOrders->count();
        $totalPendapatan = $transaksis->sum('total') + $customOrders->sum('total');

        // Hitung ringkasan harian, mingguan, bulanan untuk dashboard
        $totalHarian = $this->hitungTotal(Carbon::now()->startOfDay(), Carbon::now()->endOfDay());
        $totalMingguan = $this->hitungTotal(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
        $totalBulanan = $this->hitungTotal(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth());

        return view('admin.laporan.index', compact(
            'transaksis',
            'customOrders',
            'filter',
            'totalTransaksi',
            'totalPendapatan',
            'totalHarian',
            'totalMingguan',
            'totalBulanan'
        ));
    }

    /**
     * Menghitung total transaksi dan pendapatan dari transaksi dan custom order dalam rentang tanggal
     */
    private function hitungTotal($startDate, $endDate)
    {
        $transaksi = Transaksi::whereBetween('created_at', [$startDate, $endDate])->get();
        $custom = CustomOrder::whereBetween('created_at', [$startDate, $endDate])->get();

        return [
            'transaksi' => $transaksi->count() + $custom->count(),
            'pendapatan' => $transaksi->sum('total') + $custom->sum('total'),
        ];
    }
}
