<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\VerificationCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Register Donatur Baru
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => $request->password, // AUTO-HASH oleh User.php
            'role' => 'donatur',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ], 201);
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah'
            ], 401);
        }

        // Hapus token lama
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    /**
     * Get User Profile
     */
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }

    /**
     * Update User Profile
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $request->user()->id,
            'no_hp' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $updateData = [];

        if ($request->filled('nama')) {
            $updateData['nama'] = $request->nama;
        }

        if ($request->filled('email')) {
            $updateData['email'] = $request->email;
        }

        if ($request->filled('no_hp')) {
            $updateData['no_hp'] = $request->no_hp;
        }

        if ($request->filled('password')) {
            $updateData['password'] = $request->password; // AUTO-HASH oleh User.php
        }

        if (!empty($updateData)) {
            $user->update($updateData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $user
        ]);
    }

    /**
     * Lupa Password - Kirim Kode Verifikasi ke Email
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak terdaftar',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        // Generate kode 6 digit
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'verification_code' => $code,
            'verification_code_expires_at' => Carbon::now()->addMinutes(15),
        ]);

        // Kirim email
        try {
            Mail::to($user->email)->send(new VerificationCodeMail($code, $user->nama));
            
            return response()->json([
                'success' => true,
                'message' => 'Kode verifikasi telah dikirim ke email Anda'
            ]);
        } catch (\Exception $e) {
            // Jika gagal kirim email, tetap return success dengan kode (untuk development)
            return response()->json([
                'success' => true,
                'message' => 'Kode verifikasi telah dikirim ke email Anda',
                'debug_code' => $code // Hapus di production
            ]);
        }
    }

    /**
     * Verifikasi Kode
     */
    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|string|min:4|max:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan'
            ], 404);
        }

        // Cek apakah kode cocok
        if ($user->verification_code !== $request->code) {
            return response()->json([
                'success' => false,
                'message' => 'Kode verifikasi salah'
            ], 400);
        }

        // Cek apakah kode sudah expired
        if ($user->verification_code_expires_at < Carbon::now()) {
            return response()->json([
                'success' => false,
                'message' => 'Kode verifikasi sudah kadaluarsa. Silakan minta kode baru.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kode verifikasi valid'
        ]);
    }

    /**
     * Reset Password
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|string|min:4|max:6',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan'
            ], 404);
        }

        // Cek kode verifikasi
        if ($user->verification_code !== $request->code) {
            return response()->json([
                'success' => false,
                'message' => 'Kode verifikasi salah'
            ], 400);
        }

        // Cek expired
        if ($user->verification_code_expires_at < Carbon::now()) {
            return response()->json([
                'success' => false,
                'message' => 'Kode verifikasi sudah kadaluarsa'
            ], 400);
        }

        // Update password dan hapus kode verifikasi
        $user->update([
            'password' => $request->password, // AUTO-HASH oleh model
            'verification_code' => null,
            'verification_code_expires_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset. Silakan login dengan password baru.'
        ]);
    }
}