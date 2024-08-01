<?php

namespace App\Http\Controllers;

use App\Mail\LetterAccepted;
use App\Mail\LetterRejected;
use App\Mail\OutgoingLetterMail;
use App\Models\CategoryIncomingLetter;
use App\Models\CategoryOutgoingLetter;
use App\Models\DispotitionLetter;
use App\Models\IncomingLetter;
use App\Models\OutgoingLetter;
use Illuminate\Support\Facades\DB;
use App\Models\SifatIncomingLetter;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $users = User::where('role', 'pegawai')->get();
        return view('Kadiv.Surat-Masuk.index', compact('sifats', 'categories', 'categoryOutgoingLetters', 'users'));
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

            if ($request->tanggal) {
                $data->whereDate('tanggal_surat_masuk', $request->tanggal);
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

    public function Disposition(Request $request, $uuid)
    {
        ini_set('max_execution_time', 300); // Set maximum execution time to 300 seconds (5 minutes)

        $suratMasuk = IncomingLetter::where('uuid', $uuid)->first();
        $suratMasukData_id = IncomingLetter::where('uuid', $uuid)->value('id');

        if (!$suratMasuk) {
            return response()->json(['message' => 'Incoming letter not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'Tugas' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'user_id' => 'required|array',
            'user_id.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error.', 'errors' => $validator->errors()], 422);
        }

        // Ambil semua nama pengguna yang terkait dengan user_id dalam satu query
        $userNames = User::whereIn('id', $request->user_id)->pluck('name', 'id');

        DB::beginTransaction();

        try {
            $filePath = $request->file('file')->store('public/disposisi_letters');

            $nomerSuratDisposisi = $this->generateNoSuratDis();
            $updateStatus = IncomingLetter::where('uuid', $uuid)->update(['disposition_status' => 'Disposition Sent']);

            $existingUserNames = [];

            foreach ($request->user_id as $userId) {
                // Cek apakah kombinasi user_id dan letter_id sudah ada
                $existingEntry = DispotitionLetter::where('user_id', $userId)
                    ->where('letter_id', $suratMasukData_id)
                    ->exists();

                if ($existingEntry) {
                    // Tambahkan nama pengguna ke array jika sudah ada
                    $existingUserNames[] = $userNames[$userId] ?? 'Unknown';
                } else {
                    $nomerSuratDisposisiIDX = $this->generateNoSuratIdxDis();
                    DispotitionLetter::create([
                        'letter_id' => $suratMasukData_id,
                        'nomer_surat_disposisi' => $nomerSuratDisposisi,
                        'nomer_surat_disposisi_idx' => $nomerSuratDisposisiIDX,
                        'Tanggal Disposisi' => now()->toDateString(),
                        'user_id' => $userId,
                        'Tugas' => $request->Tugas,
                        'file' => $filePath,
                    ]);
                }
            }

            if (!empty($existingUserNames)) {
                // Jika ada nama pengguna yang sudah ada, rollback dan kembalikan pesan kesalahan
                DB::rollback();
                $namesList = implode(', ', $existingUserNames);
                return response()->json(['message' => 'Terjadi Kesalahan.', 'errors' => ['user_id' => ["Pegawai berikut sudah memiliki disposisi untuk surat ini: $namesList."]]], 422);
            }

            DB::commit();

            return response()->json(['message' => 'Outgoing letter uploaded and sent successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to upload outgoing letter.', 'error' => $e->getMessage()], 500);
        }
    }





    private function generateNoSuratIdxDis()
    {
        $newNoSuratIdx = null;
        $attempts = 0;

        do {
            // Mengunci tabel untuk mencegah race condition
            $latestSurat = DispotitionLetter::lockForUpdate()->orderBy('created_at', 'desc')->first();
            $latestNoSuratIdx = $latestSurat ? (int)substr($latestSurat->nomer_surat_disposisi_idx, 0, 4) : 0;
            $newNoSuratIdx = str_pad($latestNoSuratIdx + 1, 4, '0', STR_PAD_LEFT);

            $fixedPart = 'SDP';
            $monthRoman = $this->convertToRoman(now()->month);
            $year = now()->year;

            $generatedIdx = "{$newNoSuratIdx}/{$fixedPart}/{$monthRoman}/{$year}";
            $attempts++;

            if ($attempts > 100) {
                throw new \Exception('Failed to generate unique surat index');
            }
        } while (DispotitionLetter::where('nomer_surat_disposisi_idx', $generatedIdx)->exists());

        return $generatedIdx;
    }


    private function generateNoSuratDis()
    {
        $newNoSurat = null;
        $attempts = 0; // Log the number of attempts

        do {
            $latestSurat = DispotitionLetter::lockForUpdate()->orderBy('created_at', 'desc')->first();
            $latestNoSuratIdx = $latestSurat ? (int)substr($latestSurat->nomer_surat_keluar, 0, 4) : 0;
            $newNoSuratIdx = str_pad($latestNoSuratIdx + 1, 4, '0', STR_PAD_LEFT);

            $fixedPart = 'SDP';
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

    public function show($uuid)
    {
        $incomingLetter = IncomingLetter::with('category', 'sifat')->where('uuid', $uuid)->first();

        if (!$incomingLetter) {
            return response()->json(['message' => 'Surat masuk tidak ditemukan.'], 404);
        }

        // Check if the letter has a corresponding outgoing letter
        $outgoingLetterExists = OutgoingLetter::where('reference_letter_id', $incomingLetter->id)->exists();
        $incomingLetter->status_sent = $outgoingLetterExists;

        return response()->json($incomingLetter);
    }
}
