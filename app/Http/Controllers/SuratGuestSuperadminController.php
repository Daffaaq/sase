<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SendEmail;
use Illuminate\Support\Str;
use App\Models\Surat;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SuratGuestSuperadminController extends Controller
{
    public function viewform()
    {
        return view('Guest.kirim_surat');
    }
    public function sendSurat(Request $request)
    {
        $request->validate([
            'nama_pengirim' => 'required',
            'email_pengirim' => 'required|email',
            'instansi_pengirim' => 'required',
            'no_telp_pengirim' => 'required',
            'no_surat' => 'required',
            'file' => 'required|file|mimes:pdf',
            'deskripsi_surat' => 'required'
        ]);

        // Mendapatkan semua data request
        $surat = $request->all();

        // Menentukan nilai tambahan
        $surat['no_surat_idx'] = $this->generateNoSuratIdx();
        $surat['tanggal_upload_surat'] = now()->toDateString();
        $surat['jam_upload_surat'] = now()->toTimeString();
        $surat['status'] = 'Menunggu';

        $originalFileName = $request->file('file')->getClientOriginalName();
        $fileExtension = $request->file('file')->getClientOriginalExtension();
        $randomString = Str::random(10); // Generate a random string

        // Create new file name with random string
        $newFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '_' . $randomString . '.' . $fileExtension;

        // Menyimpan file yang diupload
        $path = $request->file('file')->storeAs('public/surat', $newFileName);
        $surat['file_path'] = $path;

        $surat['nama_file'] = $originalFileName;
        $surat['status_letter'] = 'surat_in';

        // Menyimpan data ke database
        $surat_store = Surat::create($surat);
        // dd($surat_store);

        // Mengirim email
        Mail::to($request->email_pengirim)->send(new SendEmail($surat));

        return back()->with('success', 'Surat berhasil dikirim dan pemberitahuan email telah dikirim.');
    }

    private function generateNoSuratIdx()
    {
        // Mendapatkan nomor urut terakhir dari database dan meningkatkannya
        $latestSurat = Surat::orderBy('created_at', 'desc')->first();
        $latestNoSuratIdx = $latestSurat ? (int)substr($latestSurat->no_surat_idx, 0, 4) : 0;
        $newNoSuratIdx = str_pad($latestNoSuratIdx + 1, 4, '0', STR_PAD_LEFT);

        // Menghasilkan bagian tetap dari nomor surat
        $fixedPart = 'SOT';

        // Mendapatkan bulan dalam angka Romawi
        $monthRoman = $this->convertToRoman(now()->month);

        // Mendapatkan tahun saat ini
        $year = now()->year;

        // Menggabungkan bagian-bagian tersebut menjadi nomor surat lengkap
        return "{$newNoSuratIdx}/{$fixedPart}/{$monthRoman}/{$year}";
    }

    private function convertToRoman($month)
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $map[$month];
    }

    public function index()
    {
        return view('Superadmin.Surat_In.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Surat::select('id', 'no_surat', 'no_surat_idx', 'nama_file', 'status_letter', 'file')->where('status_letter', 'surat_in')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        return response()->json(['message' => 'Method not allowed'], 405);
    }
}
