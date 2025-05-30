<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function generate()
    {
        $path = storage_path('app/public/qr_codes/');
        
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $fileName = 'whatsapp_qr.png';

        // Generate QR Code dan simpan ke file
        QrCode::format('png')->size(300)->generate('https://wa.me/1234567890', $path . $fileName);

        // Return view dengan menampilkan QR Code yang sudah dibuat
        return view('qr_show', ['qrPath' => 'storage/qr_codes/' . $fileName]);
    }
}
