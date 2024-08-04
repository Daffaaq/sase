<?php

namespace App\Http\Controllers;

use App\Mail\LetterAccepted;
use App\Mail\LetterRejected;
use App\Mail\OutgoingLetterMail;
use App\Models\ArchiveIncomingLetter;
use App\Models\CategoryArchiveIncomingLetter;
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
use App\Services\IndexSuratMasuk;
use App\Services\ListDataSuratMasuk;
use App\Services\ArchiveLetterService;
use App\Services\DispotitionLetterService;
use App\Services\VerifSurat;
use App\Services\UploadOutgoingLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SuratMasukKadivController extends Controller
{
    // Ini adalah deklarasi dari properti kelas yang bersifat protected, sehingga bisa diakses oleh kelas itu sendiri dan juga oleh kelas turunan (subclass) yang mungkin Anda miliki.
    protected $archiveLetterService;
    protected $dispotitionLetterService;
    protected $suratMasukIndexService;

    // Ini adalah konstruktor dari kelas controller. Konstruktor adalah metode khusus yang dipanggil saat sebuah instance dari kelas dibuat.
    public function __construct(IndexSuratMasuk $suratMasukIndexService, ArchiveLetterService $archiveLetterService, DispotitionLetterService $dispotitionLetterService)
    {
        $this->archiveLetterService = $archiveLetterService;
        $this->dispotitionLetterService = $dispotitionLetterService;
        $this->suratMasukIndexService = $suratMasukIndexService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->suratMasukIndexService->getIndexData();
        return view('Kadiv.Surat-Masuk.index', $data);
    }

    public function list(Request $request)
    {
        $listDataSuratMasuk = new ListDataSuratMasuk();
        return $listDataSuratMasuk->getData($request);
    }

    public function accepted(Request $request, $uuid)
    {
        return VerifSurat::approve($uuid);
    }

    public function rejected(Request $request, $uuid)
    {
        return VerifSurat::reject($uuid);
    }

    public function uploadOutgoingLetter(Request $request, $uuid)
    {
        $uploadService = new UploadOutgoingLetter();
        return $uploadService->handleUpload($request, $uuid);
    }

    public function disposition(Request $request, $uuid)
    {
        try {
            $result = $this->dispotitionLetterService->processDispotitionLetter($request, $uuid);

            if (isset($result['status']) && $result['status'] === 'error') {
                // Handle structured error response
                return response()->json($result, 422);
            }

            return response()->json($result, 200); // Ensure the status code is an integer
        } catch (\Exception $e) {
            // Log error
            Log::error('Failed to process disposition letter: ' . $e->getMessage());

            // Ensure the status code is an integer
            $statusCode = $e->getCode() && is_int($e->getCode()) ? $e->getCode() : 500;

            return response()->json(['message' => 'Failed to process disposition letter.', 'error' => $e->getMessage()], $statusCode);
        }
    }

    public function archiveLetter(Request $request, $uuid)
    {
        DB::beginTransaction();

        try {
            // Menggunakan service untuk memproses pengarsipan surat
            $this->archiveLetterService->processArchiveLetter($request, $uuid);
            DB::commit();

            return response()->json(['message' => 'Archive letter uploaded and sent successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to upload Archive letter.', 'error' => $e->getMessage()], $e->getCode() ?: 500);
        }
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
