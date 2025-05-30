<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Custom;
use Carbon\Carbon;

class CustomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Tampil halaman custom sesuai role (admin/customer)
    public function index(Request $request)
    {
        if (auth()->user()->role === 'admin') {
            $filter = $request->query('filter', 'all');
            $query = Custom::where('status', '!=', 'ditolak');

            if ($filter === 'daily') {
                $query->whereDate('created_at', Carbon::today());
            } elseif ($filter === 'weekly') {
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($filter === 'monthly') {
                $query->whereYear('created_at', Carbon::now()->year)
                      ->whereMonth('created_at', Carbon::now()->month);
            }

            $customRequests = $query->orderByDesc('created_at')->paginate(10);

            return view('admin.custom.index', compact('customRequests', 'filter'));
        }

        // Untuk customer: ambil semua custom request milik customer
        $customRequests = Custom::where('customer_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('customer.custom.index', compact('customRequests'));
    }

    // Simpan data custom baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_wa' => ['required', 'regex:/^[0-9]+$/'],
            'desain' => 'required|string',
            'gambar_referensi' => 'nullable|image|max:2048',
        ]);

        // Format nomor WA: ganti 0 di awal jadi 62 (kode Indonesia)
        $formattedNoWa = preg_replace('/^0/', '62', $request->no_wa);
        $waUrl = 'https://wa.me/' . $formattedNoWa;

        $custom = new Custom();
        $custom->customer_id = auth()->id();
        $custom->nama = $request->nama;
        $custom->no_wa = $request->no_wa;
        $custom->desain = $request->desain;
        $custom->qr_code = $waUrl;

        if ($request->hasFile('gambar_referensi')) {
            $custom->gambar_referensi = $request->file('gambar_referensi')->store('custom_referensi', 'public');
        }

        $custom->status = 'menunggu';
        $custom->save();

        // Simpan ID custom terakhir ke session supaya bisa tampil QR code di halaman customer
        $request->session()->put('latest_id', $custom->id);

        // Redirect ke halaman index customer dengan notifikasi sukses
        return redirect()->route('customer.custom.index')
                         ->with('success', 'Pesanan berhasil dikirim! Silakan scan QR Code untuk chat WhatsApp.')
                         ->cookie('show_qr', $custom->id, 120);
                         
    }

    // Approve oleh admin
    public function approve($id)
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $custom = Custom::findOrFail($id);
        $custom->status = 'disetujui';

        // Buat link WA dengan pesan persetujuan
        $no_wa = preg_replace('/^0/', '62', $custom->no_wa);
        $pesan = urlencode("Halo $custom->nama, pesanan custom kamu telah disetujui! 🎉 Kami akan segera memprosesnya. Terima kasih!");
        $waLink = "https://wa.me/$no_wa?text=$pesan";

        $custom->qr_code = $waLink;
        $custom->save();

        return redirect()->route('admin.dashboard')->with('success', 'Pesanan disetujui dan QR disiapkan untuk customer.');
    }

    // Reject oleh admin
    public function reject($id)
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $custom = Custom::findOrFail($id);
        $custom->status = 'ditolak';
        $custom->save();

        return redirect()->route('admin.dashboard')->with('success', 'Pesanan custom ditolak.');
    }
}
