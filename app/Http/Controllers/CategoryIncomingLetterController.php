<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryIncomingLetterRequest;
use App\Http\Requests\UpdateCategoryIncomingLetterRequest;
use App\Models\CategoryIncomingLetter;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class CategoryIncomingLetterController extends Controller
{
    public function index()
    {
        return view('Superadmin.Category.IncomingLetter.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = CategoryIncomingLetter::select('uuid', 'name_jenis_surat_masuk');
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        return response()->json(['message' => 'Method not allowed'], 405);
    }

    public function store(StoreCategoryIncomingLetterRequest $request)
    {
        try {
            $category = CategoryIncomingLetter::create($request->validated());
            return response()->json(['message' => 'Category created successfully.', 'category' => $category], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create category.', 'error' => $e->getMessage()], 500);
        }
    }


    public function edit($uuid)
    {
        $user = CategoryIncomingLetter::where('uuid', $uuid)->first();
        if (!$user) {
            return response()->json(['error' => true, 'message' => 'User not found.'], 404);
        }
        return response()->json(['error' => false, 'data' => $user]);
    }

    public function update(UpdateCategoryIncomingLetterRequest $request, $uuid)
    {
        try {
            $category = CategoryIncomingLetter::where('uuid', $uuid)->first();
            if (!$category) {
                return response()->json(['error' => true, 'message' => 'Category not found.'], 404);
            }

            $category->update($request->validated());

            return response()->json(['message' => 'Category updated successfully.', 'category' => $category], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update category.', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($uuid)
    {
        $category = CategoryIncomingLetter::where('uuid', $uuid)->first();
        if (!$category) {
            return response()->json(['error' => true, 'message' => 'Category not found.'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully.'], 200);
    }
}
