<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\kirim_surat_pegawai;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class SuratPegawaiSuperadminController extends Controller
{


    public function fetchPegawai()
    {
        $pegawai = User::where('role', 'pegawai')->get(['id', 'name']);
        return response()->json(['pegawai' => $pegawai]);
    }

    public function sendSurat(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'surat_id' => 'required|exists:kirim_surat_pegawais,id',
            'pegawai' => 'required|exists:users,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Retrieve the surat
            $surat = kirim_surat_pegawai::findOrFail($request->surat_id);

            // Retrieve the pegawai (user)
            $pegawai = User::findOrFail($request->pegawai);

            // Logic to "send" the surat to the selected pegawai
            // For example, updating the surat's status and associating it with the pegawai
            $surat->judul = $request->judul;
            $surat->deskripsi = $request->deskripsi;
            $surat->status_letter = 'sent'; // Assuming you have a status 'sent' for tracking
            $surat->updated_by = auth()->user()->id; // Assuming the user is authenticated
            $surat->save();

            // Log the action for audit purposes
            Log::info('Surat sent', [
                'surat_id' => $surat->id,
                'pegawai_id' => $pegawai->id,
                'updated_by' => auth()->user()->id
            ]);

            // Commit the transaction
            DB::commit();

            // Return a successful response
            return response()->json(['message' => 'Surat sent successfully']);
        } catch (\Exception $e) {
            // Rollback the transaction on failure
            DB::rollBack();

            // Log the error for debugging
            Log::error('Failed to send surat', [
                'error' => $e->getMessage(),
                'surat_id' => $request->surat_id,
                'pegawai_id' => $request->pegawai,
            ]);

            // Return an error response
            return response()->json(['error' => 'Failed to send surat'], 500);
        }
    }

}
