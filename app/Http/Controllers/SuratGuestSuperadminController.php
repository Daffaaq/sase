<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\SendEmail;
use App\Models\CategoryIncomingLetter;
use App\Models\IncomingLetter;
use App\Models\SifatIncomingLetter;
use Illuminate\Support\Str;
use App\Models\Surat;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SuratGuestSuperadminController extends Controller
{
    private const REQUIRED_STRING = 'required|string';
    public function viewform()
    {
        $sifat_surat = SifatIncomingLetter::all();
        $category_surat = CategoryIncomingLetter::all();
        return view('Guest.kirim_surat', compact('sifat_surat', 'category_surat'));
    }

    private function getValidationRules()
    {
        return [
            'nama_pengirim' => self::REQUIRED_STRING,
            'email_pengirim' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'instansi_pengirim' => self::REQUIRED_STRING,
            'no_telp_pengirim' => 'required|regex:/^[0-9]{7,14}$/',
            'deskripsi_surat' => self::REQUIRED_STRING,
            'nomer_surat_masuk' => self::REQUIRED_STRING,
            'file' => 'required|file|mimes:pdf|max:2048',
            'sifat_surat_id' => 'required|exists:sifat_incoming_letters,id',
            'category_surat_id' => 'required|exists:category_incoming_letters,id',
        ];
    }
    public function sendSurat(Request $request)
    {
        $request->validate($this->getValidationRules());

        $file = $request->file('file');
        $path = $file->store('public/files');

        DB::beginTransaction();

        try {
            $nomerSuratMasukIdx = $this->generateNoSuratIdx();

            $surat = IncomingLetter::create([
                'nomer_surat_masuk' => $request->nomer_surat_masuk,
                'nomer_surat_masuk_idx' => $nomerSuratMasukIdx,
                'tanggal_surat_masuk' => now(),
                'nama_pengirim' => $request->nama_pengirim,
                'email_pengirim' => $request->email_pengirim,
                'keterangan' => $request->deskripsi_surat,
                'file' => $path,
                'category_surat_id' => $request->category_surat_id,
                'sifat_surat_id' => $request->sifat_surat_id,
                'status' => 'Pending',
                'disposition_status' => 'pending',
                'created_by' => auth()->id() ?: null,
                'updated_by' => auth()->id() ?: null,
            ]);

            Mail::to($request->email_pengirim)->send(new SendEmail($request->all()));

            DB::commit();

            return back()->with('success', 'Surat berhasil dikirim!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to send surat.');
        }
    }

    private function generateNoSuratIdx()
    {
        $newNoSuratIdx = null;

        do {
            $latestSurat = IncomingLetter::lockForUpdate()->orderBy('created_at', 'desc')->first();
            $latestNoSuratIdx = $latestSurat ? (int)substr($latestSurat->nomer_surat_masuk_idx, 0, 4) : 0;
            $newNoSuratIdx = str_pad($latestNoSuratIdx + 1, 4, '0', STR_PAD_LEFT);

            $fixedPart = 'SIN';
            $monthRoman = $this->convertToRoman(now()->month);
            $year = now()->year;

            $generatedIdx = "{$newNoSuratIdx}/{$fixedPart}/{$monthRoman}/{$year}";
        } while (IncomingLetter::where('nomer_surat_masuk_idx', $generatedIdx)->exists());

        return $generatedIdx;
    }

    private function convertToRoman($month)
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
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
