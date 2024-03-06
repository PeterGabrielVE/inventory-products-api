<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;


class ProductController extends Controller
{

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $products = $this->productRepository->all();
        return response()->json($products);
    }

    public function show($id)
    {
        $product = $this->productRepository->find($id);
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $product = $this->productRepository->create($request->all());
        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $product = $this->productRepository->update($id, $request->all());
        return response()->json($product, 200);
    }

    public function destroy($id)
    {
        $product = $this->productRepository->delete($id);
        return response()->json(null, 200);
    }
}
