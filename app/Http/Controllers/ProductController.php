<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;


class ProductController extends Controller
{

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        try {
            $products = $this->productRepository->all();
            return response()->json($products);
        } catch (\Exception $e) {
            return Response::json(['error' => 'Failed to retrieve categories. ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = $this->productRepository->find($id);
            return response()->json($product);
            if (!$product) {
                return Response::json(['error' => 'Category not found'], 404);
            }
        } catch (\Exception $e) {
            return Response::json(['error' => 'Failed to retrieve category. ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = $this->validateProductRequest($request);
        
        if ($validator->fails()) {
            return Response::json($validator->errors(), 422);
        }
        try {
            $product = $this->productRepository->create($request->all());
            return response()->json($product, 201);
        } catch (\Exception $e) {
            return Response::json(['error' => 'Failed to create category. ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validateProductRequest($request);

        if ($validator->fails()) {
            return Response::json($validator->errors(), 422);
        }
        try {
            $product = $this->productRepository->update($id, $request->all());
            return response()->json($product, 200);
        } catch (\Exception $e) {
            return Response::json(['error' => 'Failed to create category. ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $product = $this->productRepository->delete($id);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return Response::json(['error' => 'Failed to delete category. ' . $e->getMessage()], 400);
        }
    }

    private function validateProductRequest($request)
    {
            return Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'quantity' => 'required|integer',
                'price' => 'required|numeric|regex:/^\d{1,8}(\.\d{1,2})?$/',
                'category_id' => 'required|integer',
            ]);
    }
}
