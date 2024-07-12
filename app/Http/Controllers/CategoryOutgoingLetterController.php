<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryOutgoingLetterRequest;
use App\Http\Requests\UpdateCategoryOutgoingLetterRequest;
use App\Models\CategoryOutgoingLetter;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class CategoryOutgoingLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Superadmin.Category.OutgoingLetter.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = CategoryOutgoingLetter::select('uuid', 'name_jenis_surat_keluar');
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
    public function store(StoreCategoryOutgoingLetterRequest $request)
    {
        try {
            $category = CategoryOutgoingLetter::create($request->validated());
            return response()->json(['message' => 'Category created successfully.', 'category' => $category], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create category.', 'error' => $e->getMessage()], 500);
        }
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
    public function edit($uuid)
    {
        $category = CategoryOutgoingLetter::where('uuid', $uuid)->first();
        if (!$category) {
            return response()->json(['error' => true, 'message' => 'Category not found.'], 404);
        }
        return response()->json(['error' => false, 'data' => $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryOutgoingLetterRequest $request, $uuid)
    {
        try {
            $category = CategoryOutgoingLetter::where('uuid', $uuid)->first();
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
        $category = CategoryOutgoingLetter::where('uuid', $uuid)->first();
        if (!$category) {
            return response()->json(['error' => true, 'message' => 'Category not found.'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully.'], 200);
    }
}
