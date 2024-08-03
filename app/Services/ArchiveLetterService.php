<?php

namespace App\Services;

use App\Models\IncomingLetter;
use App\Models\ArchiveIncomingLetter;
use App\Models\DispotitionLetter;
use Illuminate\Support\Facades\DB;

class ArchiveLetterService
{
    /**
     * Process the archiving of an incoming letter.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $uuid
     * @return array
     * @throws \Exception
     */
    public function processArchiveLetter($request, $uuid)
    {
        $suratMasuk = IncomingLetter::where('uuid', $uuid)->first();
        $suratMasukData_id = IncomingLetter::where('uuid', $uuid)->value('id');

        if (!$suratMasuk) {
            throw new \Exception('Incoming letter not found.', 404);
        }

        $nomerSuratKeluarIdx = $this->generateNoSuratIdxArc();

        ArchiveIncomingLetter::create([
            'letter_incoming_id' => $suratMasukData_id,
            'kode_arsip_incoming' => $nomerSuratKeluarIdx,
            'date_archive_incoming' => now()->toDateString(),
            'category_incoming_id' => $request->category_incoming_id,
        ]);

        return ['message' => 'Archive letter uploaded and sent successfully.'];
    }

    /**
     * Generate a unique archive letter index.
     *
     * @return string
     * @throws \Exception
     */
    private function generateNoSuratIdxArc()
    {
        $newNoSuratIdx = null;
        $attempts = 0;

        do {
            // Lock the table to prevent race conditions
            $latestSurat = ArchiveIncomingLetter::lockForUpdate()->orderBy('created_at', 'desc')->first();
            $latestNoSuratIdx = $latestSurat ? (int)substr($latestSurat->kode_arsip_incoming, 0, 4) : 0;
            $newNoSuratIdx = str_pad($latestNoSuratIdx + 1, 4, '0', STR_PAD_LEFT);

            $fixedPart = 'SA';
            $monthRoman = $this->convertToRoman(now()->month);
            $year = now()->year;

            $generatedIdx = "{$newNoSuratIdx}/{$fixedPart}/{$monthRoman}/{$year}";
            $attempts++;

            if ($attempts > 100) {
                throw new \Exception('Failed to generate unique surat index');
            }
        } while (ArchiveIncomingLetter::where('kode_arsip_incoming', $generatedIdx)->exists() || DispotitionLetter::where('nomer_surat_disposisi_idx', $generatedIdx)->exists());

        return $generatedIdx;
    }

    /**
     * Convert a number to a Roman numeral.
     *
     * @param int $number
     * @return string
     */
    private function convertToRoman($number)
    {
        $map = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        ];
        $result = '';
        foreach ($map as $roman => $value) {
            while ($number >= $value) {
                $result .= $roman;
                $number -= $value;
            }
        }
        return $result;
    }
}
