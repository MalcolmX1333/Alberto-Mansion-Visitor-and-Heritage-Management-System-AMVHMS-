<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MattDaneshvar\Survey\Models\Entry;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRController extends Controller
{
    public function generateQrCode($id)
    {

        \Log::info((Entry::findOrFail($id))->toArray());



        $apiUrl = route('api.visits.mark-visited', ['id' => $id]);

        \Log::info($apiUrl);

        // Generate QR code as SVG (doesn't require imagick)
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->margin(1)
            ->generate($apiUrl);

        // Return as SVG response
        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml');
    }

}
