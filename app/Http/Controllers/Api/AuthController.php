<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;


/**
 * Controlador para manejar las operaciones de autenticación y autorización de la API.
 *
 * Este controlador proporciona métodos para la autenticación de usuarios, como el inicio de sesión
 * y cierre de sesión, generación y revocación de tokens de acceso.
 *
 * @package App\Http\Controllers\Api
 */
class AuthController extends Controller
{

        /**
     * Procesa la solicitud de inicio de sesión y devuelve un token de acceso.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request){
        // Validar las credenciales proporcionadas en la solicitud
        $request-> validate([
            'email' => 'required|email',
            'password' =>'required'
        ]);

        $user = User::where('email', $request->email)->first();

        // Verificar si el usuario existe
        if(!$user){
            throw ValidationException::withMessages([
                'email' => ['The provide credentials are incorrect.']
            ]);
        }
        // Verificar si la contraseña proporcionada coincide con la almacenada en la base de datos
        if(!Hash::check($request->password, $user->password)){
            throw ValidationException::withMessages([
                'email' => ['The provide credentials are incorrect.']
            ]);
        }
        // Crear un token de acceso para el usuario
        $token = $user->createToken('api-token')->plainTextToken;

        // Devolver el token como respuesta
        return response()->json([
            'token' => $token
        ]);

    }

    /**
     * Cierra la sesión del usuario y revoca el token de acceso.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]); 
    }
}
