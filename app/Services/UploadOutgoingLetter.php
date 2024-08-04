<?php

namespace App\Services;

use App\Models\IncomingLetter;
use App\Models\OutgoingLetter;
use App\Mail\OutgoingLetterMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UploadOutgoingLetter
{
    public function handleUpload(Request $request, $uuid)
    {
        ini_set('max_execution_time', 300); // Set maximum execution time to 300 seconds (5 minutes)

        $suratMasuk = IncomingLetter::where('uuid', $uuid)->first();
        $suratMasukData_id = IncomingLetter::where('uuid', $uuid)->value('id');

        if (!$suratMasuk) {
            return response()->json(['message' => 'Incoming letter not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'keterangan' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
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
        $attempts = 0; // Log the number of attempts

        do {
            $latestSurat = OutgoingLetter::lockForUpdate()->orderBy('created_at', 'desc')->first();
            $latestNoSuratIdx = $latestSurat ? (int)substr($latestSurat->nomer_surat_keluark_idx, 0, 4) : 0;
            $newNoSuratIdx = str_pad($latestNoSuratIdx + 1, 4, '0', STR_PAD_LEFT);

            $fixedPart = 'SOT';
            $monthRoman = $this->convertToRoman(now()->month);
            $year = now()->year;

            $generatedIdx = "{$newNoSuratIdx}/{$fixedPart}/{$monthRoman}/{$year}";
            $attempts++;

            if ($attempts > 100) { // Add a condition to break the loop if it runs too many times
                throw new \Exception('Failed to generate unique surat index');
            }
        } while (OutgoingLetter::where('nomer_surat_keluark_idx', $generatedIdx)->exists());

        return $generatedIdx;
    }

    private function generateNoSurat()
    {
        $newNoSurat = null;
        $attempts = 0; // Log the number of attempts

        do {
            $latestSurat = OutgoingLetter::lockForUpdate()->orderBy('created_at', 'desc')->first();
            $latestNoSuratIdx = $latestSurat ? (int)substr($latestSurat->nomer_surat_keluar, 0, 4) : 0;
            $newNoSuratIdx = str_pad($latestNoSuratIdx + 1, 4, '0', STR_PAD_LEFT);

            $fixedPart = 'SOT';
            $middlePart = 'SASE';
            $monthRoman = $this->convertToRoman(now()->month);
            $year = now()->year;

            $newNoSurat = "{$newNoSuratIdx}/{$fixedPart}/{$middlePart}/{$monthRoman}/{$year}";
            $attempts++;

            if ($attempts > 100) { // Add a condition to break the loop if it runs too many times
                throw new \Exception('Failed to generate unique surat number');
            }
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
}
