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
            
            return DataTables::of($request)
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
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateSuratInternal(Request $request, $id)
    {
        // dd($request->all());
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroySuratInternal($id)
    {
        // Find the existing surat based on id
        
    }
}
