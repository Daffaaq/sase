<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class SuratSuperadminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Superadmin.Surat-internal.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Surat::select('id', 'no_surat', 'no_surat_idx', 'nama_file', 'status_letter', 'file')->where('status_letter', 'surat_internal')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        return response()->json(['message' => 'Method not allowed'], 405);
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
    public function storeSuratInternal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:pdf,doc,docx|max:5120', // file validation
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Generate no_surat and no_surat_idx
        $currentMonth = date('n'); // Get current month as a number (1-12)
        $currentYear = date('Y'); // Get current year
        $romawiMonths = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        $romawiMonth = $romawiMonths[$currentMonth]; // Convert month to Roman numeral

        $lastSurat = Surat::where('status_letter', 'surat_internal')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSurat) {
            $lastNumber = intval(explode('/', $lastSurat->no_surat)[0]);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            $newNumberIdx = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
            $newNumberIdx = '0001';
        }

        $no_surat = $newNumber . '/SIN/' . $romawiMonth . '/' . $currentYear;
        $no_surat_idx = $newNumberIdx . '/SIN/' . $romawiMonth . '/' . $currentYear;

        // Get original file name
        $originalFileName = $request->file('file')->getClientOriginalName();
        $fileExtension = $request->file('file')->getClientOriginalExtension();
        $randomString = Str::random(10); // Generate a random string

        // Create new file name with random string
        $newFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '_' . $randomString . '.' . $fileExtension;
        $filePath = 'surat_files/' . $newFileName;
        $disk = 'public'; // Ganti dengan nama disk yang diinginkan, misalnya 's3'

        // Simpan file ke dalam disk storage yang diinginkan
        Storage::disk($disk)->put($filePath, file_get_contents($request->file('file')));

        $currentDate = now()->toDateString(); // format: YYYY-MM-DD
        $currentTime = now()->toTimeString(); // format: HH:MM:SS

        $surat = Surat::create([
            'no_surat' => $no_surat,
            'no_surat_idx' => $no_surat_idx,
            'file' => $filePath,
            'nama_file' => $originalFileName,
            'status_letter' => 'surat_internal',
            'tanggal_upload_surat' => $currentDate,
            'jam_upload_surat' => $currentTime,
            'created_by' => auth()->user()->id,
            'created_at' => now(),
        ]);

        return response()->json(['message' => 'Surat created successfully.', 'surat' => $surat], 200);
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
    public function editSuratInternal(string $id)
    {
        $surat = Surat::find($id);
        if (!$surat) {
            return response()->json(['error' => true, 'message' => 'surat not found.'], 404);
        }
        return response()->json(['error' => false, 'data' => $surat]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateSuratInternal(Request $request, $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // file validation
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the existing surat based on id
        $surat = Surat::find($id);

        if (!$surat) {
            return response()->json(['message' => 'Surat not found.'], 404);
        }

        $updateData = [
            'updated_by' => auth()->user()->id,
            'updated_at' => now(),
            // Add other fields that need to be updated here
        ];

        if ($request->hasFile('file')) {
            // Get original file name
            $originalFileName = $request->file('file')->getClientOriginalName();
            $fileExtension = $request->file('file')->getClientOriginalExtension();
            $randomString = Str::random(10); // Generate a random string

            // Create new file name with random string
            $newFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '_' . $randomString . '.' . $fileExtension;
            $filePath = 'surat_files/' . $newFileName;
            $disk = 'public'; // Ganti dengan nama disk yang diinginkan, misalnya 's3'

            // Simpan file ke dalam disk storage yang diinginkan
            Storage::disk($disk)->put($filePath, file_get_contents($request->file('file')));

            // Add file_path and nama_file to the update data
            $updateData['file'] = $filePath;
            $updateData['nama_file'] = $originalFileName;
        }

        // Update the surat with the new data
        $surat->update($updateData);

        return response()->json(['message' => 'Surat updated successfully.', 'surat' => $surat], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroySuratInternal($id)
    {
        // Find the existing surat based on id
        $surat = Surat::find($id);

        if (!$surat) {
            return response()->json(['message' => 'Surat not found.'], 404);
        }

        // Delete the file from storage
        if (Storage::exists($surat->file_path)) {
            Storage::delete($surat->file_path);
        }

        // Delete the surat entry from the database
        $surat->delete();

        return response()->json(['message' => 'Surat deleted successfully.'], 200);
    }
}
