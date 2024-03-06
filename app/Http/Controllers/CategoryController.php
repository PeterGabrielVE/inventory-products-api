<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $categories = $this->categoryRepository->all();
        return response()->json($categories);
    }

    public function show($id)
    {
        $category = $this->categoryRepository->find($id);
        return response()->json($category);
    }

    public function store(Request $request)
    {
        $category = $this->categoryRepository->create($request->all());
        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $category = $this->categoryRepository->update($id, $request->all());
        return response()->json($category, 200);
    }

    public function destroy($id)
    {
        $category = $this->categoryRepository->delete($id);
        return response()->json(null, 200);
    }
}
