<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
class AuthUserController extends Controller
{
    public function showLoginForm()
    {
        return view('loginpage');   
    }

    public function showDashboardOwner()
    {
        return view('dashboardOwner');   
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|',
            'password' => 'required|string|min:8|',
            'role_id' => 'required|integer',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        // Buat token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Simpan token ke HttpOnly Cookie
        return response()->json([
            'message'  => 'Login berhasil',
            'role_id'  => $user->role_id,
            'user'     => $user,    // Kirim seluruh data user
            'token' => $token,
        ])->cookie('auth_token', $token, 60*24, '/', null, false, true); 
        // last true = HttpOnly
    }

    public function loginMobile(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Email atau password salah'
        ], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    // Cukup kembalikan JSON, tanpa cookie
    return response()->json([
        'message' => 'Login berhasil',
        'user'    => $user,    // Kirim seluruh data user
        'token'   => $token,
    ], 200);
}

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function userList(Request $request)
    {
        $users = User::all();
        return response()->json($users);
    }
}
