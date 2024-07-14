<?php

namespace App\Http\Controllers;

use App\Mail\LetterAccepted;
use App\Mail\LetterRejected;
use App\Models\IncomingLetter;
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
        return view('Kadiv.Surat-Masuk.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = IncomingLetter::select('uuid', 'nomer_surat_masuk', 'nomer_surat_masuk_idx', 'tanggal_surat_masuk');
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
    public function show(string $id)
    {
        //
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
