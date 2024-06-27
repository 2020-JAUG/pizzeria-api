<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Exception;


class AuthController extends Controller
{

    public function register(Request $request)
    {
        $attributes = $request->all();

        DB::beginTransaction();
        try {

            $data = $request->validate([
                'name' => 'required|min:4',
                'last_name' => 'required',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create($data);

            $token = encrypt($user->email);

            SendEmailJob::dispatch($user->email, 'Verificar email', [
                'name' => $user->name,
                'url' =>  env("APP_FRONTEND") . '/login',
                'verify' => env("APP_BACKEND") . '/verifyEmail/token=' . $token
            ], 'user_email_verify');
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
        return response()->json(['user' => $user], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|min:8',
        ]);

        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $user = auth()->user();

        return response()->json([
            'token' => $token,
            'user' => $user
        ], Response::HTTP_OK);
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
