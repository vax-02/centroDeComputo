<?php

namespace App\Http\Controllers;

use App\Models\user;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function show(user $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(user $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, user $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(user $user)
    {
        //
    }
    public function login(Request $request){
        $user = DB::table('users')->where('user', $request->input('user'))->first();
        if ($user && Hash::check($request->input('password'), $user->password)) {
            // Generar el payload del token
            $payload = [
                'sub' => $user->id,
                'user' => $user->user,
                'iat' => time(),
                'exp' => time() + (60 * 60), // 1 hora de duración
            ];

            // Crear token con la clave secreta
            $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'lastname' => $user->lastname,
                    'rol' => $user->rol
                ]
            ]);
            
        }
        // Si no coincide
        return response()->json([
            'error' => 'Credenciales inválidas'
        ], 401);
    }
    
    public function materias(Request $request)
{
    // Obtener el token desde el header Authorization: Bearer <token>
    $token = $request->bearerToken();

    if (!$token) {
        return response()->json(['error' => 'Token no proporcionado'], 400);
    }

    try {
        // Decodificar el token usando la clave secreta y algoritmo HS256
        $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

        $subjets = DB::table('subjects')
            ->where('user_id', $decoded->sub)
            ->get();

        return response()->json([
            'message' => 'Token válido.',
            'user_id' => $decoded->sub,
            'user' => $decoded->user,
            'materias' => $subjets 
        ]);

    } catch (\Firebase\JWT\ExpiredException $e) {
        return response()->json(['error' => 'Token expirado'], 401);
    } catch (\Firebase\JWT\SignatureInvalidException $e) {
        return response()->json(['error' => 'Firma del token inválida'], 401);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Token inválido o malformado', 'details' => $e->getMessage()], 401);
    }
}

    public function materia(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token no proporcionado'], 400);
        }

        try {
            // Decodificar el token usando la clave secreta y algoritmo HS256
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            $subjet = DB::table('subjects')
            ->where('user_id', $decoded->sub)
            ->where('id',$request->subject_id)
            ->first();

        return response()->json([
            'message' => 'Token válido.',
            'materia' => $subjet 
        ]);

    } catch (\Firebase\JWT\ExpiredException $e) {
        return response()->json(['error' => 'Token expirado'], 401);
    } catch (\Firebase\JWT\SignatureInvalidException $e) {
        return response()->json(['error' => 'Firma del token inválida'], 401);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Token inválido o malformado', 'details' => $e->getMessage()], 401);
    }
}

}
