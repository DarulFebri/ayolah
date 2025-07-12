<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class KajurAuthController extends Controller
{
    /**
     * Menampilkan form login kajur.
     */
    public function loginForm()
    {
        return view('kajur.login');
    }

    /**
     * Memproses percobaan login kajur.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'kajur';

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('kajur.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    /**
     * Memproses logout kajur.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('kajur.login');
    }

    // --- Bagian Lupa Sandi / Reset Password dengan OTP ---

    /**
     * Menampilkan form untuk memasukkan email saat lupa sandi.
     */
    public function forgotPasswordForm()
    {
        return view('kajur.forgot_password');
    }

    /**
     * Mengirim OTP ke email kajur untuk proses reset password.
     * OTP disimpan di tabel `users`.
     */
    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email ini tidak terdaftar sebagai email kajur.',
        ]);

        $user = User::where('email', $request->email)->where('role', 'kajur')->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'Email ini tidak terdaftar sebagai email kajur.',
            ])->withInput($request->only('email'));
        }

        $otp = Str::random(6);
        $otpExpiresAt = Carbon::now()->addMinutes(5);

        $user->update([
            'otp' => $otp,
            'otp_expires_at' => $otpExpiresAt,
        ]);

        try {
            Mail::to($user->email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim OTP reset password ke '.$user->email.': '.$e->getMessage());

            return back()->withErrors([
                'email' => 'Gagal mengirim kode OTP. Silakan coba lagi nanti.',
            ])->withInput($request->only('email'));
        }

        return redirect()->route('kajur.otp.verify.form', ['email' => $user->email])
            ->with('success', 'Kode OTP untuk reset password telah dikirim ke email Anda. Silakan cek kotak masuk Anda (termasuk folder spam).');
    }

    /**
     * Menampilkan form verifikasi OTP.
     */
    public function showOtpVerifyForm(Request $request)
    {
        if (! $request->has('email')) {
            return redirect()->route('kajur.forgot.password.form')->with('error', 'Akses tidak sah. Email tidak ditemukan.');
        }

        return view('kajur.otp_verify', ['email' => $request->email]);
    }

    /**
     * Memproses verifikasi kode OTP.
     * OTP diverifikasi di tabel `users`.
     * Jika sukses, user akan diberikan token reset password di tabel `users`.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->where('role', 'kajur')->first();

        if (! $user) {
            return back()->withErrors(['otp' => 'Email tidak ditemukan atau tidak terhubung ke akun pengguna.'])->withInput($request->only('email', 'otp'));
        }

        if ($user->otp === $request->otp && Carbon::now()->lessThan($user->otp_expires_at)) {
            $user->update([
                'otp' => null,
                'otp_expires_at' => null,
            ]);

            $resetToken = Str::random(60);
            $user->forceFill([
                'remember_token' => $resetToken,
            ])->save();

            return redirect()->route('kajur.password.reset.form', ['token' => $resetToken, 'email' => $user->email])
                ->with('success', 'Kode OTP berhasil diverifikasi. Silakan atur password baru Anda.');
        }

        return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kadaluarsa. Silakan coba ulang untuk mendapatkan kode baru.'])->withInput($request->only('email', 'otp'));
    }

    /**
     * Mengirim ulang kode OTP.
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->where('role', 'kajur')->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Email kajur tidak ditemukan atau tidak terhubung ke akun pengguna.'])->withInput($request->only('email'));
        }

        $otp = Str::random(6);
        $otpExpiresAt = Carbon::now()->addMinutes(5);

        $user->update([
            'otp' => $otp,
            'otp_expires_at' => $otpExpiresAt,
        ]);

        try {
            Mail::to($user->email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim ulang OTP ke '.$user->email.': '.$e->getMessage());

            return back()->withErrors([
                'email' => 'Gagal mengirim ulang kode OTP. Silakan coba lagi nanti.',
            ])->withInput($request->only('email'));
        }

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.')->withInput($request->only('email'));
    }

    /**
     * Menampilkan form untuk mereset password setelah OTP diverifikasi.
     */
    public function showResetPasswordForm(Request $request)
    {
        $token = $request->route('token');
        $email = $request->query('email');

        if (! $token || ! $email) {
            return redirect()->route('kajur.forgot.password.form')
                ->with('error', 'Tautan reset password tidak valid atau tidak lengkap. Silakan mulai proses dari awal.');
        }

        $user = User::where('email', $email)
            ->where('remember_token', $token)
            ->where('role', 'kajur')
            ->first();

        if (! $user) {
            return redirect()->route('kajur.forgot.password.form')
                ->with('error', 'Tautan reset password tidak valid atau sudah kadaluarsa. Silakan mulai proses dari awal.');
        }

        return view('kajur.reset_password', compact('email', 'token'));
    }

    /**
     * Memproses permintaan reset password baru.
     * Mengupdate password di tabel `users`.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Log::info('Reset Password Request (Kajur):', $request->all());

        $user = User::where('email', $request->email)
            ->where('remember_token', $request->token)
            ->where('role', 'kajur')
            ->first();

        if (! $user) {
            Log::warning('Reset Password (Kajur): User not found or token invalid', ['email' => $request->email, 'token' => $request->token]);

            return back()->withErrors(['email' => 'Tautan reset password tidak valid atau sudah kadaluarsa. Silakan mulai proses Lupa Sandi dari awal.']);
        }

        Log::info('Reset Password (Kajur): User found. ID:', ['id' => $user->id, 'email' => $user->email]);

        $user->password = Hash::make($request->password);
        $user->remember_token = null;

        try {
            $user->save();
            Log::info('Reset Password (Kajur): Password updated successfully for user ID:', ['id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('Reset Password (Kajur): Failed to save password for user ID: '.$user->id.' Error: '.$e->getMessage());

            return back()->withErrors(['password' => 'Terjadi kesalahan saat menyimpan password baru. Silakan coba lagi.']);
        }

        return redirect()->route('kajur.password.reset.success')->with('success', 'Password Anda berhasil diubah. Silakan login dengan password baru Anda.');
    }

    /**
     * Menampilkan halaman sukses setelah reset password.
     */
    public function passwordResetSuccess()
    {
        return view('kajur.password_reset_success');
    }
}
