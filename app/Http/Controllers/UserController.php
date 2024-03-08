<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Iniciar sesión de usuario",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="user@example.com",
     *                     description="Correo electrónico del usuario"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     example="password123",
     *                     description="Contraseña del usuario"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Inicio de sesión exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvY2FsaG9zdDo4MDAwL2FwaS9sb2dpbiIsImlhdCI6MTY0NzIyMjExNSwiZXhwIjoxNjQ3MjI1NzE1LCJuYmYiOjE2NDcyMjIxMTUsImp0aSI6IjA1ZWVmYmIwLTQ5NDItNGE1YS05ZGJkLTNlOWQ2MzVhYTY2MCJ9.WLgQjAqEn0KMR6ytVkRIfO4mcybS2lWDK8IQ-dJK4O8")
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Credenciales incorrectas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('API Token')->plainTextToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }
}
