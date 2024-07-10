<?php

namespace App\Http\Controllers;

use App\Models\CategoryIncomingLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryIncomingLetterController extends Controller
{
    public function index()
    {
        $categories = CategoryIncomingLetter::all();
        return response()->json(['categories' => $categories], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_jenis_surat_masuk' => 'required|string|max:255',
            'created_by' => 'nullable|integer',
            'updated_by' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = CategoryIncomingLetter::create($validator->validated());

        return response()->json(['message' => 'Category created successfully.', 'category' => $category], 201);
    }
}
