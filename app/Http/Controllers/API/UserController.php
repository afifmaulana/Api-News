<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('users');
    }


    public function users()
    {
        $user = User::all();
        return response()->json([
           'message' => 'Berhasil menampilkan semua User',
           'status' => true,
           'data' => $user
        ]);
    }

    public function profile()
    {
            $user = User::where('id', Auth::user()->id)->first();
            return response()->json([
                'message' => 'successfully get user is login',
                'status' => true,
                'data' => new UserResource($user)
            ],200);
    }

    public function UpdateProfile(Request $request)
    {
            $user = Auth::user();
            $user->name = $request->name;
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json([
                'message' => 'Berhasil mengubah profil',
                'status' => true,
                'data' => $user
                ], 200);
    }
}
