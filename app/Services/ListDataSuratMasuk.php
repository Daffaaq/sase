<?php

namespace App\Services;

use App\Models\IncomingLetter;
use App\Models\OutgoingLetter;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class ListDataSuratMasuk
{
    public function getData(Request $request)
    {
        // Pastikan permintaan adalah AJAX
        if (!$request->ajax()) {
            return response()->json(['message' => 'Method not allowed'], 405);
        }

        // Siapkan filter berdasarkan permintaan
        $filters = [
            'sifat' => $request->sifat,
            'kategori' => $request->kategori,
            'tanggal' => $request->tanggal,
        ];

        // Dapatkan data yang sudah difilter menggunakan metode filter pada model
        $data = IncomingLetter::filter($filters)
            ->whereDoesntHave('archive')
        ->get();
        $data_ids = $data->pluck('id');

        // Cari surat keluar terkait
        $dataoutgoing = OutgoingLetter::whereIn('reference_letter_id', $data_ids)->pluck('reference_letter_id')->toArray();

        // Tambahkan status_sent ke setiap surat
        foreach ($data as $letter) {
            $letter->status_sent = in_array($letter->id, $dataoutgoing);
        }

        // Kembalikan data dalam format DataTables
        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }
}
