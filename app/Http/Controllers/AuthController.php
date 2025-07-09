<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showRegistrationFormMahasiswa()
    {
        return view('auth.register-mahasiswa');
    }

    public function registerMahasiswa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required|unique:mahasiswas',
            'nama_lengkap' => 'required',
            'jurusan' => 'required',
            'prodi' => 'required',
            'jenis_kelamin' => 'required',
            'kelas' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
        ]);

        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $request->nim,
            'nama_lengkap' => $request->nama_lengkap,
            'prodi' => $request->prodi,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kelas' => $request->kelas,
        ]);

        Auth::login($user);

        return redirect('/mahasiswa/dashboard');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role == 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role == 'dosen') {
                return redirect()->intended('/dosen/dashboard');
            } elseif ($user->role == 'mahasiswa') {
                return redirect()->intended('/mahasiswa/dashboard');
            }

            return redirect()->intended('/dashboard'); // Default redirect
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function showLoginForm()
    {
        return view('auth.login'); // Pastikan view ini ada
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
