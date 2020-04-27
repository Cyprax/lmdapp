<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['login']]);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        //Merging login|email into request
        $emailOrMatricule = request()->input('username');
        $username = filter_var($emailOrMatricule, FILTER_VALIDATE_EMAIL) ? 'email' : 'matricule';
        request()->merge([$username => $emailOrMatricule]);

        $credentials = request()->only([$username, 'password']);

        //Attempting credentials
            //$user = User::where('email', $request->email)->first();
        $user = User::where($username, request()->input($username))->first();

        if (!$user || !Hash::check(request()->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        $token = $user->createToken('lmdapp-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        request()->user()->tokens()->delete();
        return response('Successfully logged out', 200);
        //return response()->json(['message' => 'Successfully logged out']);
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function principal()
    {
        $user = request()->user();
        return response()->json($user);
    }


    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        //return $this->respondWithToken(auth('api')->refresh());
    }

}
