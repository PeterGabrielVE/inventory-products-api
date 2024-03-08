<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

/**
* @OA\Info(title="API Inventario", version="1.0")
*
* @OA\Server(url="http://swagger.local")
*/

class CategoryController extends Controller
{
    
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
        * @OA\Get(
        *     path="/api/categories",
        *     summary="Obtener todos las categorías",
        *     @OA\Response(response="200", description="Lista de categorias"),
        *     @OA\Response(response="500", description="Categorias no encontrados"),
        * )

    */
    public function index()
    {
        try {
            $categories = $this->categoryRepository->all();
            return response()->json($categories);
        } catch (\Exception $e) {
            return Response::json(['error' => 'Failed to retrieve categories. ' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Obtener una categoría por ID",
     *     description="Obtiene una categoría específica por su ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría obtenida correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1"),
     *             @OA\Property(property="name", type="string", example="Category Name"),
     *             @OA\Property(property="description", type="string", example="Descripcion Nueva categoría")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Category not found")
     *         )
     *     )
     * )
     */
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

 /**
 * @OA\Post(
 *     path="/api/categories",
 *     summary="Crear una nueva categoría",
 *     tags={"Categories"},
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="Category Name"),
 *             @OA\Property(property="description", type="string", example="Descripcion Nueva categoría")
 *         )
 *     ),
 *     @OA\Response(response="201", description="Categoría creada exitosamente"),
 *     @OA\Response(response="422", description="Error de validación")
 * )
 */
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

    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     summary="Actualizar una categoría existente",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la categoría a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Nueva categoría"),
     *             @OA\Property(property="description", type="string", example="Descripcion Nueva categoría")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Categoría actualizada exitosamente"),
     *     @OA\Response(response="400", description="Error al actualizar la categoría"),
     *     @OA\Response(response="422", description="Error de validación")
     * )
    */
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

    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     summary="Eliminar una categoría existente",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la categoría a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="Categoría eliminada exitosamente"),
     *     @OA\Response(response="400", description="Error al eliminar la categoría")
     * )
     */
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
