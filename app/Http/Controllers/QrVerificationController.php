<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * Generates QR verification codes for VeriVote NG results.
 *
 * Encodes the public verification URL for a recorded result so an observer
 * or member of the public can scan the code and open its read-only integrity
 * record.
 */
class QrVerificationController extends Controller
{
    /**
     * Generate a QR code that points to the public verification route.
     */
    public function show(Result $result): Response
    {
        $verificationUrl = route('public.verify', [
            'result' => $result->id,
        ]);

        return response(
            QrCode::format('svg')
                ->size(320)
                ->margin(2)
                ->generate($verificationUrl)
        )->header('Content-Type', 'image/svg+xml');
    }
}