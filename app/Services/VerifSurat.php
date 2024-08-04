<?php

namespace App\Services;

use App\Models\IncomingLetter;
use Illuminate\Support\Facades\Mail;
use App\Mail\LetterAccepted;
use App\Mail\LetterRejected;

class VerifSurat
{
    public static function approve($uuid)
    {
        $suratMasuk = IncomingLetter::where('uuid', $uuid)->first();

        if (!$suratMasuk) {
            return response()->json(['message' => 'Surat masuk not found.'], 404);
        }

        $suratMasuk->update([
            'status' => 'Approved',
        ]);

        Mail::to($suratMasuk->email_pengirim)->send(new LetterAccepted($suratMasuk));

        return response()->json(['message' => 'Surat masuk approved successfully.'], 200);
    }

    public static function reject($uuid)
    {
        $suratMasuk = IncomingLetter::where('uuid', $uuid)->first();

        if (!$suratMasuk) {
            return response()->json(['message' => 'Surat masuk not found.'], 404);
        }

        $suratMasuk->update([
            'status' => 'Rejected',
        ]);

        Mail::to($suratMasuk->email_pengirim)->send(new LetterRejected($suratMasuk));

        return response()->json(['message' => 'Surat masuk rejected successfully.'], 200);
    }
}
