<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('user')->get();
        return response()->json($categories);
    }
    

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = Category::create($request->all());
        return response()->json($category, 201);
    }

    public function show($id)
    {
        $category = Category::find($id);
        if (is_null($category)) {
            return response()->json(['Category tidak ditemukan'], 404);
        }
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (is_null($category)) {
            return response()->json(['Category tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category->update($request->all());
        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (is_null($category)) {
            return response()->json(['Category tidak ditemukan'], 404);
        }

        $category->delete();
        return response()->json(['Category sudah dihapus']);
    }
}
