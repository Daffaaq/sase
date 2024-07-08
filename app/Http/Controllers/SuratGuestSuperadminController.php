<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SendEmail;
use App\Models\IncomingLetter;
use Illuminate\Support\Str;
use App\Models\Surat;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SuratGuestSuperadminController extends Controller
{
    public function viewform()
    {
        return view('Guest.kirim_surat');
    }
    public function sendSurat(Request $request)
    {
    }

    private function generateNoSuratIdx()
    {
        // Mendapatkan nomor urut terakhir dari database dan meningkatkannya
        $latestSurat = IncomingLetter::orderBy('created_at', 'desc')->first();
        $latestNoSuratIdx = $latestSurat ? (int)substr($latestSurat->no_surat_idx, 0, 4) : 0;
        $newNoSuratIdx = str_pad($latestNoSuratIdx + 1, 4, '0', STR_PAD_LEFT);

        // Menghasilkan bagian tetap dari nomor surat
        $fixedPart = 'SOT';

        // Mendapatkan bulan dalam angka Romawi
        $monthRoman = $this->convertToRoman(now()->month);

        // Mendapatkan tahun saat ini
        $year = now()->year;

        // Menggabungkan bagian-bagian tersebut menjadi nomor surat lengkap
        return "{$newNoSuratIdx}/{$fixedPart}/{$monthRoman}/{$year}";
    }

    private function convertToRoman($month)
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $map[$month];
    }

    public function index()
    {
        return view('Superadmin.Surat_In.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of($request)
                ->addIndexColumn()
                ->make(true);
        }
        return response()->json(['message' => 'Method not allowed'], 405);
    }
}
