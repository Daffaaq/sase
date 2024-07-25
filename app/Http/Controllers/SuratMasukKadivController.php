<?php

namespace App\Http\Controllers;

use App\Mail\LetterAccepted;
use App\Mail\LetterRejected;
use App\Mail\OutgoingLetterMail;
use App\Models\CategoryIncomingLetter;
use App\Models\CategoryOutgoingLetter;
use App\Models\IncomingLetter;
use App\Models\OutgoingLetter;
use Illuminate\Support\Facades\DB;
use App\Models\SifatIncomingLetter;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuratMasukKadivController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sifats = SifatIncomingLetter::all(); // Sesuaikan dengan model yang digunakan
        $categories = CategoryIncomingLetter::all(); // Sesuaikan dengan model yang digunakan
        $categoryOutgoingLetters = CategoryOutgoingLetter::all();
        return view('Kadiv.Surat-Masuk.index', compact('sifats', 'categories', 'categoryOutgoingLetters'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = IncomingLetter::with('category', 'sifat')->select('id', 'uuid', 'nomer_surat_masuk', 'nomer_surat_masuk_idx', 'tanggal_surat_masuk', 'sifat_surat_id', 'category_surat_id', 'status', 'disposition_status');

            if ($request->sifat) {
                $data->where('sifat_surat_id', $request->sifat);
            }

            if ($request->kategori) {
                $data->where('category_surat_id', $request->kategori);
            }

            $dataCollection = $data->get();
            $data_ids = $dataCollection->pluck('id');

            $dataoutgoing = OutgoingLetter::whereIn('reference_letter_id', $data_ids)->pluck('reference_letter_id')->toArray();

            foreach ($dataCollection as $letter) {
                $letter->status_sent = in_array($letter->id, $dataoutgoing);
            }

            return DataTables::of($dataCollection)
                ->addIndexColumn()
                ->make(true);
        }
        return response()->json(['message' => 'Method not allowed'], 405);
    }


    public function accepted(Request $request, $uuid)
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

    public function rejected(Request $request, $uuid)
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

    public function uploadOutgoingLetter(Request $request, $uuid)
    {
        $suratMasuk = IncomingLetter::where('uuid', $uuid)->first();
        $suratMasukData_id = IncomingLetter::where('uuid', $uuid)->value('id');

        if (!$suratMasuk) {
            return response()->json(['message' => 'Incoming letter not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'keterangan' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx',
            'category_surat_id' => 'required|exists:category_outgoing_letters,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error.', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $filePath = $request->file('file')->store('public/outgoing_letters');

            $nomerSuratKeluarIdx = $this->generateNoSuratIdx();
            $nomerSuratKeluar = $this->generateNoSurat();

            $outgoingLetter = OutgoingLetter::create([
                'reference_letter_id' => $suratMasukData_id,
                'nomer_surat_keluar' => $nomerSuratKeluar,
                'nomer_surat_keluark_idx' => $nomerSuratKeluarIdx,
                'tanggal_surat_keluar' => now()->toDateString(),
                'nama_penerima' => $suratMasuk->nama_pengirim,
                'email_penerima' => $suratMasuk->email_pengirim,
                'keterangan' => $request->keterangan,
                'file' => $filePath,
                'category_surat_id' => $request->category_surat_id,
                'status' => 'Sent',
            ]);

            Mail::to($suratMasuk->email_pengirim)->send(new OutgoingLetterMail($outgoingLetter));

            DB::commit();

            return response()->json(['message' => 'Outgoing letter uploaded and sent successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to upload outgoing letter.', 'error' => $e->getMessage()], 500);
        }
    }




    private function generateNoSuratIdx()
    {
        $newNoSuratIdx = null;

        do {
            $latestSurat = OutgoingLetter::lockForUpdate()->orderBy('created_at', 'desc')->first();
            $latestNoSuratIdx = $latestSurat ? (int)substr($latestSurat->nomer_surat_keluar_idx, 0, 4) : 0;
            $newNoSuratIdx = str_pad($latestNoSuratIdx + 1, 4, '0', STR_PAD_LEFT);

            $fixedPart = 'SOT';
            $monthRoman = $this->convertToRoman(now()->month);
            $year = now()->year;

            $generatedIdx = "{$newNoSuratIdx}/{$fixedPart}/{$monthRoman}/{$year}";
        } while (OutgoingLetter::where('nomer_surat_keluark_idx', $generatedIdx)->exists());

        return $generatedIdx;
    }

    private function generateNoSurat()
    {
        $newNoSurat = null;

        do {
            $latestSurat = OutgoingLetter::lockForUpdate()->orderBy('created_at', 'desc')->first();
            $latestNoSuratIdx = $latestSurat ? (int)substr($latestSurat->nomer_surat_keluar, 0, 4) : 0;
            $newNoSuratIdx = str_pad($latestNoSuratIdx + 1, 4, '0', STR_PAD_LEFT);

            $fixedPart = 'SOT';
            $middlePart = 'SASE';
            $monthRoman = $this->convertToRoman(now()->month);
            $year = now()->year;

            $newNoSurat = "{$newNoSuratIdx}/{$fixedPart}/{$middlePart}/{$monthRoman}/{$year}";
        } while (OutgoingLetter::where('nomer_surat_keluar', $newNoSurat)->exists());

        return $newNoSurat;
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
    public function show($uuid)
    {
        $incomingLetter = IncomingLetter::with('category', 'sifat')->where('uuid', $uuid)->first();

        if (!$incomingLetter) {
            return response()->json(['message' => 'Surat masuk tidak ditemukan.'], 404);
        }

        return response()->json($incomingLetter);
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
