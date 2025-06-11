<?php

namespace App\Http\Controllers;

use App\Models\Subject;

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
    public function verificar(Request $request){
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token no proporcionado'], 400);
        }

        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            $user = User::find($request->user_id);
            $status = false;
            if($user){
                if (Hash::check($request->password, $user->password)) {
                    $status = true;
                }
            }
            return response()->json([
                'message' => 'Token válido.',
                'id' => $decoded->sub,
                'validate' => $status,
            ]); 


        } catch (\Firebase\JWT\ExpiredException $e) {
            return response()->json(['error' => 'Token expirado'], 401);
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            return response()->json(['error' => 'Firma del token inválida'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token inválido o malformado', 'details' => $e->getMessage()], 401);
        }
    }
    public function materias(Request $request){
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token no proporcionado'], 400);
        }

        try {
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

    public function materiaEstudiante(Request $request) 
    {
        $token = $request->bearerToken();
        $subject_id = $request->input('subject_id'); 

        if (!$token) {
            return response()->json(['error' => 'Token no proporcionado'], 400);
        }

        if (!$subject_id) {
            return response()->json(['error' => 'ID de materia no proporcionado'], 400);
        }

        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $userId = $decoded->sub; 

            $materia = DB::table('subjects_users')
                ->join('subjects', 'subjects_users.subject_id', '=', 'subjects.id')
                ->join('users', 'subjects.user_id', '=', 'users.id')
                ->where('subjects_users.user_id', $userId) 
                ->where('subjects.id', $subject_id) 
                ->select(
                    'subjects.id',
                    'subjects.title',
                    'subjects.name',
                    'subjects.paralelo',
                    'subjects_users.semestre',
                    'users.id as docente_id',
                    'users.name as docente_name',
                    'users.lastname as docente_lastname'
                )
                ->first();

            if (!$materia) {
                return response()->json([
                    'message' => 'Materia no encontrada o el estudiante no está registrado en esta materia.',
                    'estudiante_id' => $userId,
                    'subject_id_solicitado' => $subject_id,
                ], 404);
            }

            $materiaFormateada = [
                'id' => $materia->id,
                'title' => $materia->title,
                'name' => $materia->name,
                'paralelo' => $materia->paralelo,
                'semestre' => $materia->semestre,
                'docente' => [
                    'id' => $materia->docente_id,
                    'name' => $materia->docente_name,
                    'lastname' => $materia->docente_lastname,
                ],
            ];

            return response()->json([
                'message' => 'Materia del estudiante obtenida correctamente',
                'estudiante_id' => $userId,
                'materia' => $materiaFormateada,
            ], 200);

        } catch (\Firebase\JWT\ExpiredException $e) {
            return response()->json(['error' => 'Token expirado'], 401);
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            return response()->json(['error' => 'Firma del token inválida'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token inválido o malformado', 'details' => $e->getMessage()], 401);
        }
    }

    public function estudiantes(Request $request){
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token no proporcionado'], 400);
        }

        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            $students = Subject::with('users')->find($request->subject_id);    
            return response()->json([
                'message' => 'Token válido.',
                'estudiantes' => $students 
            ]);

        } catch (\Firebase\JWT\ExpiredException $e) {
            return response()->json(['error' => 'Token expirado'], 401);
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            return response()->json(['error' => 'Firma del token inválida'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token inválido o malformado', 'details' => $e->getMessage()], 401);
        }
    }
    
    public function registro(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token no proporcionado'], 400);
        }

        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $userId = $decoded->sub;

            $materias = DB::table('subjects_users')
                ->join('subjects', 'subjects_users.subject_id', '=', 'subjects.id')
                ->join('users', 'subjects.user_id', '=', 'users.id')
                ->where('subjects_users.user_id', $userId) 
                ->select(
                    'subjects.id',
                    'subjects.title',
                    'subjects.name',
                    'subjects.paralelo',
                    'subjects_users.semestre',
                    'users.id as docente_id',
                    'users.name as docente_name',
                    'users.lastname as docente_lastname'
                )
                ->get();

            $materiasFormateadas = $materias->map(function ($materia) {
                return [
                    'id' => $materia->id,
                    'title' => $materia->title,
                    'name' => $materia->name,
                    'paralelo' => $materia->paralelo,
                    'semestre' => $materia->semestre,
                    'docente' => [
                        'id' => $materia->docente_id,
                        'name' => $materia->docente_name,
                        'lastname' => $materia->docente_lastname,
                    ],
                ];
            });

            if ($materiasFormateadas->isEmpty()) {
                return response()->json([
                    'message' => 'El estudiante no está registrado en ninguna materia.',
                    'estudiante_id' => $userId,
                    'materias' => [],
                ], 200);
            }

            return response()->json([
                'message' => 'Materias del estudiante obtenidas correctamente',
                'estudiante_id' => $userId,
                'materias' => $materiasFormateadas,
            ]);
        } catch (\Firebase\JWT\ExpiredException $e) {
            return response()->json(['error' => 'Token expirado'], 401);
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            return response()->json(['error' => 'Firma del token inválida'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token inválido o malformado', 'details' => $e->getMessage()], 401);
        }
    }

   public function estudiante(Request $request){
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token no proporcionado'], 400);
        }

        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            $student = User::find($request->user_id);
            return response()->json([
                'message' => 'Token válido.',
                'estudiante' => $student
            ]);

        } catch (\Firebase\JWT\ExpiredException $e) {
            return response()->json(['error' => 'Token expirado'], 401);
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            return response()->json(['error' => 'Firma del token inválida'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token inválido o malformado', 'details' => $e->getMessage()], 401);
        }
    }

    public function estudiantesSeleccion(Request $request){
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token no proporcionado'], 400);
        }

        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            $students = User::whereIn('id', $request->ids)->get();
            return response()->json([
                'message' => 'Token válido.',
                'estudiantes' => $students
            ]);

        } catch (\Firebase\JWT\ExpiredException $e) {
            return response()->json(['error' => 'Token expirado'], 401);
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            return response()->json(['error' => 'Firma del token inválida'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token inválido o malformado', 'details' => $e->getMessage()], 401);
        }
    }
    
    public function PublicSubjects()
    {
        try {
            // Seleccionar solo los campos necesarios: id, title, name, paralelo
            // Y filtrar por status activo si tus materias tienen un campo 'status'
            $publicSubjects = DB::table('subjects')
                                ->select('id', 'title', 'name', 'paralelo')
                                // ->where('status', 1) // Descomenta si tienes un campo 'status' para materias públicas
                                ->get();

            // La clave 'materias' es la que espera tu frontend (Livewire)
            return response()->json([
                'materias' => $publicSubjects
            ], 200);

        } catch (\Exception $e) {
            // Registrar el error para depuración
            \Log::error('Error al obtener materias públicas: ' . $e->getMessage());
            return response()->json(['error' => 'No se pudieron obtener las materias públicas.'], 500);
        }
    }
}
