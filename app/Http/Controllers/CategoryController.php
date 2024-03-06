<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class CategoryController extends Controller
{
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        try {
            $categories = $this->categoryRepository->all();
            return response()->json($categories);
        } catch (\Exception $e) {
            return Response::json(['error' => 'Failed to retrieve categories. ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $category = $this->categoryRepository->find($id);
            if (!$category) {
                return Response::json(['error' => 'Category not found'], 404);
            }
            return response()->json($category);
        } catch (\Exception $e) {
            return Response::json(['error' => 'Failed to retrieve category. ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = $this->validateCategoryRequest($request);
        
        if ($validator->fails()) {
            return Response::json($validator->errors(), 422);
        }
        
        try {
            $category = $this->categoryRepository->create($request->all());
            return response()->json($category, 201);
        } catch (\Exception $e) {
            return Response::json(['error' => 'Failed to create category. ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validateCategoryRequest($request);
    
        if ($validator->fails()) {
            return Response::json($validator->errors(), 422);
        }
        
        try {
            $category = $this->categoryRepository->update($id, $request->all());
            return response()->json($category, 200);
        } catch (\Exception $e) {
            return Response::json(['error' => 'Failed to delete category. ' . $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $category = $this->categoryRepository->delete($id);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return Response::json(['error' => 'Failed to delete category. ' . $e->getMessage()], 400);
        }
    }

    private function validateCategoryRequest($request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);
    }
}
