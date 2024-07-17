<?php

namespace App\Http\Controllers;

use App\Mail\LetterAccepted;
use App\Mail\LetterRejected;
use App\Models\CategoryIncomingLetter;
use App\Models\IncomingLetter;
use App\Models\SifatIncomingLetter;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class SuratMasukKadivController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sifats = SifatIncomingLetter::all(); // Sesuaikan dengan model yang digunakan
        $categories = CategoryIncomingLetter::all(); // Sesuaikan dengan model yang digunakan
        return view('Kadiv.Surat-Masuk.index', compact('sifats', 'categories'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = IncomingLetter::with('category', 'sifat')->select('uuid', 'nomer_surat_masuk', 'nomer_surat_masuk_idx', 'tanggal_surat_masuk', 'sifat_surat_id', 'category_surat_id', 'status', 'disposition_status');
            if ($request->sifat) {
                $data->where('sifat_surat_id', $request->sifat);
            }

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
