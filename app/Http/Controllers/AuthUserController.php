<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use Exception;
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
    $validator = Validator::make($request->all(), [
        'name'     => 'required|string|max:255',
        'email'    => 'required|string|email|unique:users,email',
        'password' => 'required|string|min:6',
        'role_id'  => 'required|exists:roles,id_roles'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role_id'  => $request->role_id
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Karyawan berhasil ditambahkan.',
        'data'    => $user->load('role')
    ]);
}

    public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'message' => 'Email atau password salah'
        ], 401);
    }

    $request->session()->regenerate();

    return response()->json([
        'message' => 'Login berhasil',
        'role_id' => auth()->user()->role_id,
        'user'    => auth()->user()
    ]);
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
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }


    public function userList(Request $request)
    {
        $users = User::with('role')->paginate(10);
        return response()->json($users);
    }
    public function roles()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => "required|email|unique:users,email,{$id},id_user",
            'role_id'  => 'required|exists:roles,id_roles',
            'password' => 'nullable|string|min:6', // opsional
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false, 
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::findOrFail($id);

            $user->name    = $request->name;
            $user->email   = $request->email;
            $user->role_id = $request->role_id;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Data karyawan berhasil diperbarui.',
                'data'    => $user->load('role'),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal memperbarui karyawan.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Karyawan berhasil dihapus.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menghapus karyawan.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
