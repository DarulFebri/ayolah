<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengajuan; // Ini boleh di sini jika dipakai di dashboard Mahasiswa
use App\Models\Dosen;     // Ini boleh di sini jika dipakai di dashboard Mahasiswa
use App\Models\Mahasiswa; // Ini boleh di sini jika dipakai di dashboard Mahasiswa
use App\Imports\MahasiswaImport;
use App\Exports\MahasiswaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Models\User;     // Pastikan model User diimpor
use Illuminate\Support\Facades\Log; // Add this line at the top
use Illuminate\Validation\Rule; // Tambahkan ini untuk Rule::unique
use App\Models\Prodi; // Import the Prodi model
use App\Models\Kelas; // Import the Kelas model

use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{

    public function export()
    {
        // Nama file yang akan diunduh
        $fileName = 'data_mahasiswa_' . date('Ymd_His') . '.xlsx';

        // Unduh file Excel menggunakan kelas export yang sudah dibuat
        return Excel::download(new MahasiswaExport, $fileName);
    }

    // Method untuk memproses file Excel mahasiswa
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv|max:2048', // Validasi file Excel
        ]);

        try {
            Excel::import(new MahasiswaImport, $request->file('file')); // Proses impor
            return redirect()->back()->with('success', 'Data mahasiswa berhasil diimpor!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                // Ambil header kolom yang menyebabkan kegagalan
                $attribute = $failure->attribute();
                $errorMessage = implode(', ', $failure->errors());
                $errors[] = 'Baris ' . $failure->row() . ' (Kolom: ' . $attribute . '): ' . $errorMessage;
            }
            return redirect()->back()->with('error', 'Gagal mengimpor data mahasiswa. Ada kesalahan validasi: <ul><li>' . implode('</li><li>', $errors) . '</li></ul>');
        } catch (\Exception $e) {
            Log::error('Kesalahan impor mahasiswa umum: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data mahasiswa: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        // Start with a base query for Mahasiswa
        $query = Mahasiswa::with(['prodi', 'kelas']);

        // Check if a search term is present in the request
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;

            // Apply search filter
            // We use `where` for the first condition and `orWhere` for subsequent conditions
            // to search across multiple columns.
            $query->where('nim', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nama_lengkap', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('prodi', function($q) use ($searchTerm) {
                      $q->where('nama_prodi', 'like', '%' . $searchTerm . '%');
                  });
            
        }

        // Get the filtered (or unfiltered) students
        $mahasiswas = $query->get(); // If you have many students, consider using ->paginate(10) instead of ->get()

        // Pass the students data to the view
        return view('admin.mahasiswa.index', compact('mahasiswas')); // Adjust view path if necessary
    }

    public function editProfile()
    {
        if (!Auth::check() || Auth::user()->role !== 'mahasiswa') {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $mahasiswa = $this->getLoggedInMahasiswa();
        $prodis = Prodi::all(); // Fetch all program studies

        return view('mahasiswa.edit_profile', compact('mahasiswa', 'prodis'));
    }

    

    /**
     * Menampilkan form login mahasiswa.
     * Route: GET /mahasiswa/login
     * Name: mahasiswa.login
     */
    public function loginForm()
    {
        return view('mahasiswa.login');
    }

    /**
     * Memproses percobaan login mahasiswa.
     * Route: POST /mahasiswa/login
     * Name: mahasiswa.login
     */

     public function changePasswordForm()
     {
         return view('mahasiswa.change-password'); // Akan membuat file ini di Langkah 3
     }
 
     /**
      * Memproses perubahan sandi mahasiswa.
      * Route: POST /mahasiswa/password/change
      * Name: mahasiswa.password.change
      */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed', // 'confirmed' akan mencari new_password_confirmation
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'new_password.required' => 'Kata sandi baru wajib diisi.',
            'new_password.min' => 'Kata sandi baru harus minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Kata sandi berhasil diubah!');
    }
     
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba temukan user di tabel users berdasarkan email dan role 'mahasiswa'
        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'mahasiswa';

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Redirect ke dashboard mahasiswa setelah login berhasil
            return redirect()->intended(route('mahasiswa.dashboard'));
        }

        // Kembali ke halaman login dengan pesan error jika kredensial tidak cocok
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email')); // Pertahankan input email
    }

    // --- Bagian Lupa Sandi / Reset Password dengan OTP ---

    /**
     * Menampilkan form untuk memasukkan email saat lupa sandi.
     * Route: GET /mahasiswa/forgot-password
     * Name: mahasiswa.forgot.password.form
     */
    public function forgotPasswordForm()
    {
        return view('mahasiswa.forgot_password');
    }

    /**
     * Mengirim OTP ke email mahasiswa untuk proses reset password.
     * OTP disimpan di tabel `mahasiswas`.
     * Route: POST /mahasiswa/forgot-password
     * Name: mahasiswa.send.reset.otp
     */
    public function sendResetOtp(Request $request)
    {
        $request->validate([
            // Validasi bahwa email harus ada di tabel 'mahasiswas'
            'email' => 'required|email|exists:mahasiswas,email',
        ], [
            'email.exists' => 'Email ini tidak terdaftar sebagai email mahasiswa.',
        ]);

        // Cari data mahasiswa berdasarkan email yang diinput
        $mahasiswa = Mahasiswa::where('email', $request->email)->first();

        // Cek apakah mahasiswa ditemukan dan memiliki user_id yang valid
        if (!$mahasiswa || !$mahasiswa->user_id) {
            return back()->withErrors([
                'email' => 'Email mahasiswa tidak ditemukan atau tidak terhubung ke akun pengguna.',
            ])->withInput($request->only('email'));
        }

        // Generate OTP baru (6 digit random string)
        $otp = Str::random(6);
        // Set waktu kadaluarsa OTP (misalnya 5 menit dari sekarang)
        $otpExpiresAt = Carbon::now()->addMinutes(5);

        // Simpan OTP dan waktu kadaluarsa ke tabel `mahasiswas`
        $mahasiswa->update([
            'otp' => $otp,
            'otp_expires_at' => $otpExpiresAt,
        ]);

        // Kirim OTP via email menggunakan Mailable
        try {
            Mail::to($mahasiswa->email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            // Log error untuk debugging lebih lanjut
            \Log::error('Gagal mengirim OTP reset password ke ' . $mahasiswa->email . ': ' . $e->getMessage());
            return back()->withErrors([
                'email' => 'Gagal mengirim kode OTP. Silakan coba lagi nanti ya.',
            ])->withInput($request->only('email'));
        }

        // In your sendResetOtp method, you have this:
        return redirect()->route('mahasiswa.otp.verify.form', ['email' => $mahasiswa->email])
        ->with('success', 'Kode OTP untuk reset password telah dikirim ke email Anda. Silakan cek kotak masuk Anda (termasuk folder spam).');
    }

    /**
     * Menampilkan form verifikasi OTP.
     * Route: GET /mahasiswa/otp/verify?email=...
     * Name: mahasiswa.otp.verify.form
     */
    public function showOtpVerifyForm(Request $request)
    {
        // Pastikan ada parameter email di URL
        if (!$request->has('email')) {
            return redirect()->route('mahasiswa.forgot.password.form')->with('error', 'Akses tidak sah. Email tidak ditemukan.');
        }
        return view('mahasiswa.otp_verify', ['email' => $request->email]);
    }

    /**
     * Memproses verifikasi kode OTP.
     * OTP diverifikasi di tabel `mahasiswas`.
     * Jika sukses, user akan diberikan token reset password di tabel `users`.
     * Route: POST /mahasiswa/otp/verify
     * Name: mahasiswa.otp.verify
     */

     public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        $mahasiswa = Mahasiswa::where('email', $request->email)->first();

        if (!$mahasiswa) {
            return back()->withErrors(['otp' => 'Email tidak ditemukan.'])->withInput($request->only('email', 'otp'));
        }

        if ($mahasiswa->otp === $request->otp && Carbon::now()->lessThan($mahasiswa->otp_expires_at)) {
            $mahasiswa->update([
                'otp' => null,
                'otp_expires_at' => null,
            ]);

            $user = $mahasiswa->user;

            if (!$user) {
                return back()->withErrors(['otp' => 'Akun pengguna tidak ditemukan terkait dengan mahasiswa ini.'])->withInput($request->only('email', 'otp'));
            }

            $resetToken = Str::random(60);
            $user->forceFill([
                'remember_token' => $resetToken,
            ])->save();

            // --- CHANGE START ---
            // Ensure that the token is passed as a route parameter and email as a query parameter.
            // Laravel's route helper does this automatically for route parameters defined with {}
            // and puts others as query parameters. This is already correct.
            return redirect()->route('mahasiswa.password.reset.form', ['token' => $resetToken, 'email' => $user->email])
                             ->with('success', 'Kode OTP berhasil diverifikasi. Silakan atur password baru Anda.');
            // --- CHANGE END ---

        }

        return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kadaluarsa. Silakan coba ulang untuk mendapatkan kode baru.'])->withInput($request->only('email', 'otp'));
    }

    /**
     * Mengirim ulang kode OTP.
     * Route: POST /mahasiswa/otp/resend
     * Name: mahasiswa.otp.resend
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Cari mahasiswa berdasarkan email
        $mahasiswa = Mahasiswa::where('email', $request->email)->first();

        if (!$mahasiswa || !$mahasiswa->user_id) {
            return back()->withErrors(['email' => 'Email mahasiswa tidak ditemukan atau tidak terhubung ke akun pengguna.'])->withInput($request->only('email'));
        }

        // Generate OTP baru dan waktu kadaluarsa
        $otp = Str::random(6);
        $otpExpiresAt = Carbon::now()->addMinutes(5);

        // Update OTP di tabel `mahasiswas`
        $mahasiswa->update([
            'otp' => $otp,
            'otp_expires_at' => $otpExpiresAt,
        ]);

        // Kirim OTP via email
        try {
            Mail::to($mahasiswa->email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim ulang OTP ke ' . $mahasiswa->email . ': ' . $e->getMessage());
            return back()->withErrors([
                'email' => 'Gagal mengirim ulang kode OTP. Silakan coba lagi nanti.',
            ])->withInput($request->only('email'));
        }

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.')->withInput($request->only('email'));
    }

    /**
     * Menampilkan form untuk mereset password setelah OTP diverifikasi.
     * Token dan email user diterima dari URL.
     * Route: GET /mahasiswa/reset-password/{token}?email=...
     * Name: mahasiswa.password.reset.form
     */
    public function showResetPasswordForm(Request $request)
    {
        // --- CHANGE START ---
        // Ensure we correctly retrieve 'token' from the route parameters
        // and 'email' from the query parameters.
        $token = $request->route('token'); // Get from the URL segment e.g., /reset-password/{token}
        $email = $request->query('email'); // Get from the query string e.g., ?email=...

        // Perform initial validation. If token or email are missing from the URL,
        // redirect them back to the forgot password form.
        if (!$token || !$email) {
            return redirect()->route('mahasiswa.forgot.password.form')
                             ->with('error', 'Tautan reset password tidak valid atau tidak lengkap. Silakan mulai proses dari awal.');
        }

        // Now, verify the token and email against the database.
        // Ensure you are using the correct User model here if Mahasiswa and User are separate.
        $user = User::where('email', $email)
                    ->where('remember_token', $token)
                    ->first();

        if (!$user) {
            // If user not found with that token/email combo, the link is invalid/expired
            return redirect()->route('mahasiswa.forgot.password.form')
                             ->with('error', 'Tautan reset password tidak valid atau sudah kadaluarsa. Silakan mulai proses dari awal.');
        }

        // Pass the retrieved email and token to the view.
        return view('mahasiswa.reset_password', compact('email', 'token'));
        // --- CHANGE END ---
    }

    /**
     * Memproses permintaan reset password baru.
     * Mengupdate password di tabel `users`.
     * Route: POST /mahasiswa/reset-password
     * Name: mahasiswa.password.reset
     */

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Log the incoming request data for debugging
        Log::info('Reset Password Request:', $request->all());

        $user = User::where('email', $request->email)->where('remember_token', $request->token)->first();

        if (!$user) {
            Log::warning('Reset Password: User not found or token invalid', ['email' => $request->email, 'token' => $request->token]);
            return back()->withErrors(['email' => 'Tautan reset password tidak valid atau sudah kadaluarsa. Silakan mulai proses Lupa Sandi dari awal.']);
        }

        // Log user details before update
        Log::info('Reset Password: User found. ID:', ['id' => $user->id, 'email' => $user->email]);

        // Assign the new plain password
        $user->password = $request->password;

        // --- CRITICAL CHANGE: Use $user->save() after setting the password ---
        // forceFill is usually for mass assignment. For single attribute update,
        // explicitly set and then save. Also, set remember_token to null *before* saving.
        $user->remember_token = null; // Clear the token

        try {
            $user->save(); // Explicitly save the model
            Log::info('Reset Password: Password updated successfully for user ID:', ['id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('Reset Password: Failed to save password for user ID: ' . $user->id . ' Error: ' . $e->getMessage());
            return back()->withErrors(['password' => 'Terjadi kesalahan saat menyimpan password baru. Silakan coba lagi.']);
        }

        // Log user details after update (you can fetch it again from DB to confirm, or inspect $user object)
        // Note: $user->password will still show the plain text here before it's "re-fetched" from DB
        // but the actual stored value should be hashed due to the cast.
        $updatedUser = User::find($user->id); // Re-fetch to see stored hash
        Log::info('Reset Password: User after save. Hashed Password Sample:', ['hashed_password_start' => substr($updatedUser->password, 0, 10)]);


        return redirect()->route('mahasiswa.password.reset.success')->with('success', 'Password Anda berhasil diubah. Silakan login dengan password baru Anda.');
    }

    /**
     * Menampilkan halaman sukses setelah reset password.
     * Route: GET /mahasiswa/password-reset-success
     * Name: mahasiswa.password.reset.success
     */
    public function passwordResetSuccess()
    {
        return view('mahasiswa.password_reset_success');
    }

    // --- Bagian Dashboard Mahasiswa ---

    /**
     * Menampilkan dashboard mahasiswa.
     * Route: GET /mahasiswa/dashboard
     * Name: mahasiswa.dashboard
     * Middleware: auth, mahasiswa
     */
    public function dashboard()
    {
        // Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user(); // Dapatkan objek User yang sedang login
        $mahasiswa = $user->mahasiswa; // Dapatkan objek Mahasiswa terkait melalui relasi

        // Jika data mahasiswa tidak ditemukan untuk user ini, log out dan redirect
        if (!$mahasiswa) {
            Auth::logout();
            return redirect()->route('mahasiswa.login')->with('error', 'Data mahasiswa tidak ditemukan untuk akun ini. Silakan hubungi admin.');
        }

        // Mengambil pengajuan terbaru untuk ditampilkan di dashboard (contoh)
        $pengajuanTerbaru = Pengajuan::where('mahasiswa_id', $mahasiswa->id)
                                     ->orderBy('created_at', 'desc')
                                     ->limit(5)
                                     ->get();
        // Memuat relasi yang diperlukan untuk pengajuan terbaru (misal: sidang)
        $pengajuanTerbaru->load('sidang'); // Sesuaikan dengan relasi yang ada di model Pengajuan

        // Kirim data ke view dashboard
        return view('mahasiswa.dashboard', compact('pengajuanTerbaru', 'mahasiswa'));
    }

    /**
     * Menampilkan form untuk mengedit profil mahasiswa.
     * Route: GET /mahasiswa/profile/edit
     * Name: mahasiswa.profile.edit
     * Middleware: auth, mahasiswa
     */
    public function editProfileForm()
    {
        if (!Auth::check()) {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            Auth::logout();
            return redirect()->route('mahasiswa.login')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        return view('mahasiswa.edit_profile', compact('mahasiswa'));
    }

    /**
     * Memperbarui profil mahasiswa.
     * Route: POST /mahasiswa/profile/update
     * Name: mahasiswa.profile.update
     * Middleware: auth, mahasiswa
     */
    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nim' => ['required', 'string', 'max:20', Rule::unique('mahasiswas')->ignore($mahasiswa->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('mahasiswas')->ignore($mahasiswa->id)],
            'prodi_id' => 'nullable|exists:prodis,id', // Changed to prodi_id
            'angkatan' => 'nullable|integer|digits:4',
            'nomor_hp' => 'nullable|string|max:20',
            'cropped_image' => 'nullable|string',
        ]);

        $dataToUpdate = $request->only(['nama_lengkap', 'nim', 'email', 'prodi_id', 'angkatan', 'nomor_hp']);

        if ($request->filled('cropped_image')) {
            // Hapus foto lama jika ada
            if ($mahasiswa->foto_profil) {
                Storage::delete('public/' . $mahasiswa->foto_profil);
            }

            $data = $request->cropped_image;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $imageName = 'photos/' . Str::random(20) . '.png';
            Storage::disk('public')->put($imageName, $data);
            $dataToUpdate['foto_profil'] = $imageName;
            Log::info('Foto profil disimpan: ' . $imageName);
        }

        $mahasiswa->update($dataToUpdate);
        Log::info('Profil mahasiswa diperbarui: ', $dataToUpdate);

        if ($user->email !== $request->email) {
            $user->email = $request->email;
            $user->save();
        }

        return redirect()->route('mahasiswa.profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }


    /**
     * Memproses logout mahasiswa.
     * Route: POST /mahasiswa/logout
     * Name: mahasiswa.logout
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Logout user
        $request->session()->invalidate(); // Invalidasi sesi
        $request->session()->regenerateToken(); // Regenerasi token CSRF
        return redirect()->route('mahasiswa.login'); // Redirect ke halaman login
    }

    // --- Fungsi Import dan Export (jika relevan) ---
    // Pastikan Anda telah menginstal maatwebsite/excel jika menggunakan ini
    // dan telah membuat kelas MahasiswaImport/MahasiswaExport

    public function importForm()
    {
        // Ambil semua data prodi dan kelas
        $prodis = Prodi::all();
        $kelas = Kelas::all();

        // View untuk form import, kirim data prodi dan kelas
        return view('admin.mahasiswa.import', compact('prodis', 'kelas'));
    }
}
