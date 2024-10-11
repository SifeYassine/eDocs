<?php

namespace App\Http\Controllers\api\categories;

use App\Models\category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
{
    // Create a new category
    public function create(Request $request)
    {
        try {
            $validateCategory = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:categories',
                'user_id' => 'required|integer|exists:users,id',
            ]);

            if ($validateCategory->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateCategory->errors()
                ], 401);
            }

            $category = category::create([
                'name' => $request->name,
                'user_id' => $request->user_id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Category created successfully',
                'category' => $category,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // Get all categories
    public function index()
    {
        try {
            $categories = Category::all();
            return response()->json([
                'status' => true,
                'message' => 'All categories',
                'categories' => $categories
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // Update a category
    public function update(Request $request, $id)
    {
        try {
            $category = category::find($id);

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Category not found',
                ], 404);
            }

            $validateCategory = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:categories,name,' . $id,
            ]);

            if ($validateCategory->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateCategory->errors()
                ], 401);
            }

            $category->update([
                'name' => $request->name,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully',
                'category' => $category,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // Delete a category
    public function delete($id)
    {
        try {
            $category = category::find($id);

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'category not found',
                ], 404);
            }

            $category->delete();
            return response()->json([
                'status' => true,
                'message' => 'Category deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}