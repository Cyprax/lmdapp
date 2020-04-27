<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = request()->user();
        return response()->json($user);
    }

    public function roles()
    {
        $user = request()->user();
        $roles = $user->roles()->get()->map->only(['id', 'label', 'description']);
        return response()->json( $roles );
    }

    public function hasRole()
    {
        $user = request()->user();
        $roleName = request()->input('label');
        $hasRole = $user->roles()->where('label', $roleName)->get()->isNotEmpty();
        return response()->json( $hasRole );
    }

}
