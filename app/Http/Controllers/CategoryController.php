<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * List all categories (Public)
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories, 200);
    }

    /**
     * Show single category detail (Public)
     */
    public function show($id)
    {
        $category = Category::with('products')->find($id);
        if(!$category){
            return response()->json(['message' => 'Category not found.'], 404);
        }
        return response()->json($category, 200);
    }

    /**
     * Create a new category (Admin)
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        // Create category
        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Category created successfully.', 'category' => $category], 201);
    }

    /**
     * Update a category (Admin)
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if(!$category){
            return response()->json(['message' => 'Category not found.'], 404);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:categories,name,'.$id,
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        // Update category
        $category->update($request->only(['name']));

        return response()->json(['message' => 'Category updated successfully.', 'category' => $category], 200);
    }

    /**
     * Delete a category (Admin)
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if(!$category){
            return response()->json(['message' => 'Category not found.'], 404);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted successfully.'], 200);
    }
}
