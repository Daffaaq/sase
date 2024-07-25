<?php

namespace App\Http\Controllers;

use App\Models\CategoryOutgoingLetter;
use App\Models\OutgoingLetter;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class SuratKeluarKadivController extends Controller
{
    public function index()
    {
        $categories = CategoryOutgoingLetter::all();
        return view('Kadiv.Surat-Keluar.index', compact('categories'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = OutgoingLetter::with('category')->select('uuid', 'nomer_surat_keluar', 'nomer_surat_keluark_idx', 'tanggal_surat_keluar', 'category_surat_id', 'status');
            if ($request->kategori) {
                $data->where('category_surat_id', $request->kategori);
            }

            $data = $data->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        return response()->json(['message' => 'Method not allowed'], 405);
    }

    public function show($uuid)
    {
        $outgoingLetter = OutgoingLetter::with('category')->where('uuid', $uuid)->first();

        if (!$outgoingLetter) {
            return response()->json(['message' => 'Surat keluar tidak ditemukan.'], 404);
        }

        return response()->json($outgoingLetter);
    }
}
