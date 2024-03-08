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

    /**
        * @OA\Get(
        *     path="/api/products",
        *     summary="Obtener todos los productos",
        *     @OA\Response(response="200", description="Lista de productos"),
        *     @OA\Response(response="500", description="Productos no encontrados"),
        * )

    */
    public function index()
    {
        try {
            $products = $this->productRepository->all();
            return response()->json($products);
        } catch (\Exception $e) {
            return Response::json(['error' => 'Failed to retrieve categories. ' . $e->getMessage()], 500);
        }
    }

     /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Obtener un producto por ID",
     *     description="Obtiene un producto específica por su ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="producto obtenido correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1"),
     *             @OA\Property(property="name", type="string", example="Product Name"),
     *             @OA\Property(property="description", type="string", example="Product Description"),
     *             @OA\Property(property="price", type="integer", example="1"),
     *             @OA\Property(property="quantity",  type="decimal", example="10"),
     *             @OA\Property(property="category_id",  type="integer", example="1"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="producto no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Product not found")
     *         )
     *     )
     * )
     */
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

     /**
     * @OA\Post(
     *     path="/api/products",
     *     summary="Crear un nuevo producto",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"name","price","quantity","category_id"},
     *             @OA\Property(property="name", type="string", example="Product Name"),
     *             @OA\Property(property="description", type="string", example="Descripcion Nuevo producto"),
     *             @OA\Property(property="price", type="integer", example="1"),
     *             @OA\Property(property="quantity",  type="decimal", example="10"),
     *             @OA\Property(property="category_id",  type="integer", example="1"),
     *         )
     *     ),
     *     @OA\Response(response="201", description="producto creado exitosamente"),
     *     @OA\Response(response="422", description="Error de validación")
    * )
    */
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

     /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Actualizar un producto existente",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del producto a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","price","quantity","category_id"},
     *             @OA\Property(property="name", type="string", example="Update producto"),
     *             @OA\Property(property="description", type="string", example="Descripcion update producto"),
     *             @OA\Property(property="price", type="integer", example="1"),
     *             @OA\Property(property="quantity",  type="decimal", example="10"),
     *             @OA\Property(property="category_id",  type="integer", example="1"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="producto actualizada exitosamente"),
     *     @OA\Response(response="400", description="Error al actualizar la producto"),
     *     @OA\Response(response="422", description="Error de validación")
     * )
    */
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

     /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Eliminar un producto existente",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del producto a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="producto eliminado exitosamente"),
     *     @OA\Response(response="400", description="Error al eliminar el producto")
     * )
     */
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
