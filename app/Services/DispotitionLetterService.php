<?php

namespace App\Services;

use App\Models\DispotitionLetter;
use App\Models\IncomingLetter;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DispotitionLetterService
{
    /**
     * Process the disposition letter.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $uuid
     * @return array
     * @throws \Exception
     */
    public function processDispotitionLetter($request, $uuid)
    {
        // Perpanjang waktu eksekusi jika diperlukan
        ini_set('max_execution_time', 300);

        // Dapatkan surat masuk berdasarkan UUID
        $suratMasuk = IncomingLetter::where('uuid', $uuid)->first();

        if (!$suratMasuk) {
            // Buang pengecualian dengan kode status yang tepat
            throw new Exception('Incoming letter not found.', 404);
        }

        // Dapatkan ID surat masuk
        $suratMasukData_id = $suratMasuk->id;

        // Validasi data input
        $validator = Validator::make($request->all(), [
            'Tugas' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'user_id' => 'required|array',
            'user_id.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            throw new Exception('Validation error.', 422);
        }

        // Ambil semua nama pengguna terkait dengan user_id dalam satu query
        $userNames = User::whereIn('id', $request->user_id)->pluck('name', 'id');

        DB::beginTransaction();

        try {
            // Simpan file ke dalam penyimpanan
            $filePath = $request->file('file')->store('public/disposisi_letters');

            // Generate nomor surat disposisi
            $nomerSuratDisposisi = $this->generateNoSuratDis();
            IncomingLetter::where('uuid', $uuid)->update(['disposition_status' => 'Disposition Sent']);

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
                return [
                    'status' => 'error',
                    'message' => 'Terjadi Kesalahan.',
                    'errors' => [
                        'user_id' => ["Pegawai berikut sudah memiliki disposisi untuk surat ini: $namesList."]
                    ]
                ];
            }

            DB::commit();

            return ['message' => 'Disposition letter uploaded and sent successfully.'];
        } catch (Exception $e) {
            DB::rollback();
            // Hapus file jika terjadi kesalahan setelah diupload
            if (isset($filePath)) {
                Storage::delete($filePath);
            }
            throw $e; // Throw exception to controller for consistent handling
        }
    }

    /**
     * Generate a unique index for disposition surat.
     *
     * @return string
     * @throws \Exception
     */
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
                throw new Exception('Failed to generate unique surat index');
            }
        } while (DispotitionLetter::where('nomer_surat_disposisi_idx', $generatedIdx)->exists());

        return $generatedIdx;
    }

    /**
     * Generate a unique surat number for disposition.
     *
     * @return string
     * @throws \Exception
     */
    private function generateNoSuratDis()
    {
        $newNoSurat = null;
        $attempts = 0; // Log the number of attempts

        do {
            $latestSurat = DispotitionLetter::lockForUpdate()->orderBy('created_at', 'desc')->first();
            $latestNoSuratIdx = $latestSurat ? (int)substr($latestSurat->nomer_surat_disposisi, 0, 4) : 0;
            $newNoSuratIdx = str_pad($latestNoSuratIdx + 1, 4, '0', STR_PAD_LEFT);

            $fixedPart = 'SDP';
            $middlePart = 'SASE';
            $monthRoman = $this->convertToRoman(now()->month);
            $year = now()->year;

            $newNoSurat = "{$newNoSuratIdx}/{$fixedPart}/{$middlePart}/{$monthRoman}/{$year}";
            $attempts++;

            if ($attempts > 100) { // Add a condition to break the loop if it runs too many times
                throw new Exception('Failed to generate unique surat number');
            }
        } while (DispotitionLetter::where('nomer_surat_disposisi', $newNoSurat)->exists());

        return $newNoSurat;
    }

    /**
     * Convert a number to a Roman numeral.
     *
     * @param int $month
     * @return string
     */
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
