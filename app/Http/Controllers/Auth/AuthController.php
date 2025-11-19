<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Rats\Zkteco\Lib\ZKTeco;
class AuthController extends Controller
{
    public function lcslogin(Request $request) {
        $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string'
        ]);

        $credentials = $request->only('email', 'password');
        if(!Auth::attempt($credentials))
        {
        return response()->json([
            'message' => 'Email or Password is incorrect'
        ],401);
        }
        $user = Auth::user();
        
        // if($user->id != 1) {
        //     return response()->json([
        //         'message' => 'Unauthorized'
        //     ],401);
        // }
        $name = '';
        if($user->name) {
            $name = $user->name;
        }
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;

        return response()->json([
        'accessToken' =>$token,
        'token_type' => 'Bearer',
        'name' => $name,
        ]);
    }
    public function login(Request $request) {
        $request->validate([
        'username' => 'required|string',
        'password' => 'required|string'
        ]);

        $credentials = request(['username','password']);
        if(!Auth::attempt($credentials))
        {
            return response()->json([
                'message' => 'Unauthorized'
            ],401);
        }

        $user = $request->user();
        $name = '';
        if($user->info) {
            $name = $user->info->first_name.' '.$user->info->sur_name;
        }
        // if ($user->tokens()->exists()) {
        //     return response()->json(['has_token' => true], 401);
        // }
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;

        return response()->json([
        'accessToken' =>$token,
        'token_type' => 'Bearer',
        'name' => $name,
        ]);
    }
    public function logout(Request $request) {
        $user = Auth::user();
        if ($user) {
            $user->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        }

        return response()->json(['error' => 'Not authenticated'], 401);
    }

    public function users() {
     $users = User::select('id', 'name', 'email', 'created_at')
                 ->orderBy('id', 'asc')
                 ->get();

    return response()->json($users);
    }

    // CREATE
        public function storeUser(Request $request)
        {
            $validated = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return response()->json($user, 201);
        }

        // UPDATE
        public function updateUser(Request $request, User $user)
        {
            $validated = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($user->id),
                ],
                'password' => 'nullable|string|min:8',
            ]);

            $user->name  = $validated['name'];
            $user->email = $validated['email'];

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            return response()->json($user);
        }

        // DELETE
        public function destroyUser(User $user)
        {
            // optional: prevent deleting yourself
            if (Auth::id() === $user->id) {
                return response()->json(['message' => 'Cannot delete currently logged in user'], 422);
            }

            $user->delete();

            return response()->json(['message' => 'User deleted']);
        }
    public function validateToken() {
        if (Auth::check()) {
            return response()->json(['valid' => true]);
        }
    
        return response()->json(['valid' => false], 401);
    }
}
