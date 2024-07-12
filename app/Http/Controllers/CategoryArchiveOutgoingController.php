<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryArchiveOutgoingRequest;
use App\Http\Requests\UpdateCategoryArchiveOutgoingRequest;
use App\Models\CategoryArchiveOutgoingLetter;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class CategoryArchiveOutgoingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Superadmin.Category.ArchiveOutgoingLetter.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = CategoryArchiveOutgoingLetter::select('uuid', 'name_jenis_arsip_surat_keluar');
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
    public function store(StoreCategoryArchiveOutgoingRequest $request)
    {
        try {
            $category = CategoryArchiveOutgoingLetter::create($request->validated());
            return response()->json(['message' => 'Category created successfully.', 'category' => $category], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create category.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($uuid)
    {
        $user = CategoryArchiveOutgoingLetter::where('uuid', $uuid)->first();
        if (!$user) {
            return response()->json(['error' => true, 'message' => 'User not found.'], 404);
        }
        return response()->json(['error' => false, 'data' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryArchiveOutgoingRequest $request, $uuid)
    {
        try {
            $category = CategoryArchiveOutgoingLetter::where('uuid', $uuid)->first();
            if (!$category) {
                return response()->json(['error' => true, 'message' => 'Category not found.'], 404);
            }

            $category->update($request->validated());

            return response()->json(['message' => 'Category updated successfully.', 'category' => $category], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update category.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        $category = CategoryArchiveOutgoingLetter::where('uuid', $uuid)->first();
        if (!$category) {
            return response()->json(['error' => true, 'message' => 'Category not found.'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully.'], 200);
    }
}
