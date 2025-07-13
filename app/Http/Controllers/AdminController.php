<?php

namespace App\Http\Controllers;

use App\Exports\DosenExport;
use App\Exports\MahasiswaExport;
use App\Exports\SidangExport;
use App\Imports\DosenImport;
use App\Imports\MahasiswaImport;
use App\Models\Activity;
use App\Models\Dokumen; // Import the Prodi model
use App\Models\Dosen; // Import the Activity model
use App\Models\Kelas; // Import the Kelas model
use App\Models\Mahasiswa; // Import the new model
use App\Models\Pengajuan;
use App\Models\PengajuanStatusHistory;
use App\Models\Prodi;
use App\Models\Sidang;
use App\Models\User; // Perbaikan: singular
use Illuminate\Http\Request;     // Perbaikan: singular
use Illuminate\Support\Facades\Auth;    // Perbaikan: singular
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    // Fungsi logActivity (contoh implementasi sederhana jika belum ada)
    // Anda mungkin ingin memindahkannya ke Service, Trait, atau Helper
    protected function logActivity($description, $subjectType = null)
    {
        Activity::create([
            'user_id' => Auth::id(), // ID user yang sedang login
            'activity' => $description,
            'subject_type' => $subjectType,
            'subject_id' => null, // Sesuaikan jika ada ID subjek spesifik
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
    }

    protected function logPengajuanStatusChange(Pengajuan $pengajuan, $oldStatus, $newStatus, $notes = null)
    {
        PengajuanStatusHistory::create([
            'pengajuan_id' => $pengajuan->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by_user_id' => Auth::id(),
            'notes' => $notes,
        ]);
    }

    public function daftarDosen(Request $request)
    {
        $query = Dosen::with('prodi'); // Eager load prodi

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nidn', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('prodi', function ($q) use ($search) {
                        $q->where('nama_prodi', 'like', "%{$search}%");
                    });
            });
        }

        // Sorting functionality
        switch ($request->sort) {
            case 'nama_asc':
                $query->orderBy('nama', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('nama', 'desc');
                break;
            case 'nidn_asc':
                $query->orderBy('nidn', 'asc');
                break;
            case 'nidn_desc':
                $query->orderBy('nidn', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $dosens = $query->paginate(10);

        return view('admin.dosen.index', compact('dosens'));
    }

    public function importForm()
    {
        return view('admin.dosen.import');
    }

    // Method untuk memproses file Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv', // Validasi file Excel
        ]);

        try {
            Excel::import(new DosenImport, $request->file('file')); // Proses impor

            return redirect()->back()->with('success', 'Data dosen berhasil diimpor!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = 'Baris '.$failure->row().': '.implode(', ', $failure->errors());
            }

            return redirect()->back()->with('error', 'Gagal mengimpor data dosen. Ada kesalahan validasi: '.implode('; ', $errors));
        } catch (\Exception $e) {
            // Tangani error umum lainnya
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data dosen: '.$e->getMessage());
        }
    }

    public function pilihJenisPengajuanSidang()
    {
        return view('admin.pengajuan.sidang.pilih-jenis');
    }

    // New method to list TA submissions
    public function daftarPengajuanTa()
    {
        $pengajuans = Pengajuan::with('mahasiswa')
            ->where('jenis_pengajuan', 'ta')
            ->where('status', '!=', 'draft')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pengajuan.sidang.ta', compact('pengajuans'));
    }

    // New method to list PKL submissions
    public function daftarPengajuanPkl()
    {
        $pengajuans = Pengajuan::with('mahasiswa')
            ->where('jenis_pengajuan', 'pkl')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pengajuan.sidang.pkl', compact('pengajuans'));
    }

    // ... existing Pengajuan Methods (setujuiPengajuan, tolakPengajuan, detailPengajuan)
    // You might want to adjust detailPengajuan to be more generic if it's used for both types,
    // or create specific detail methods if the logic differs significantly.
    public function detailPengajuan(Pengajuan $pengajuan)
    {
        $dokumens = Dokumen::where('pengajuan_id', $pengajuan->id)->get();

        return view('admin.pengajuan.show', compact('pengajuan', 'dokumens'));
    }

    public function setujuiPengajuan(Pengajuan $pengajuan)
    {
        $oldStatus = $pengajuan->status;
        $newStatus = 'diverifikasi_admin';
        $pengajuan->update(['status' => $newStatus]);
        $this->logPengajuanStatusChange($pengajuan, $oldStatus, $newStatus, 'Pengajuan disetujui oleh Admin.');

        return back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function tolakPengajuan(Pengajuan $pengajuan)
    {
        $oldStatus = $pengajuan->status;
        $newStatus = 'ditolak_admin';
        $pengajuan->update(['status' => $newStatus]);
        $this->logPengajuanStatusChange($pengajuan, $oldStatus, $newStatus, 'Pengajuan ditolak oleh Admin.');

        return back()->with('error', 'Pengajuan berhasil ditolak.');
    }

    public function loginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'admin'; // Tambahkan role ke credentials

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function dashboard()
    {
        $totalMahasiswa = Mahasiswa::count();
        $totalDosen = Dosen::count();

        return view('admin.dashboard', compact('totalMahasiswa', 'totalDosen'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login'); // Redirect ke halaman utama atau halaman lain setelah logout
    }

    // Dibawah ini untuk CRUD mahasiswa
    public function daftarMahasiswa(Request $request)
    {
        $mahasiswas = Mahasiswa::with(['prodi', 'kelas']); // Eager load the prodi relationship

        // Sorting
        if ($request->has('sort_by') && $request->has('sort_order')) {
            $sortBy = $request->input('sort_by');
            $sortOrder = $request->input('sort_order');

            if (in_array($sortBy, ['kelas', 'jenis_kelamin'])) {
                $mahasiswas->orderBy($sortBy, $sortOrder);
            }
        }

        // Search for mahasiswa
        if ($request->has('search')) {
            $search = $request->search;
            $mahasiswas->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $mahasiswas = $mahasiswas->paginate(10); // Add pagination here

        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    public function detailMahasiswa(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load(['prodi', 'kelas']); // Eager load prodi and kelas relationships

        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    public function createMahasiswa()
    {
        $prodis = Prodi::all(); // Fetch all program studies
        $kelas = Kelas::all(); // Fetch all classes

        return view('admin.mahasiswa.create', compact('prodis', 'kelas'));
    }

    public function storeMahasiswa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required|unique:mahasiswas',
            'nama_lengkap' => 'required',
            'prodi_id' => 'required|exists:prodis,id',
            'jenis_kelamin' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
        ]);

        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $request->nim,
            'nama_lengkap' => $request->nama_lengkap,
            'prodi_id' => $request->prodi_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kelas_id' => $request->kelas_id,
        ]);

        $this->logActivity('Membuat mahasiswa baru: '.$request->nama_lengkap, 'Mahasiswa'); // Menggunakan $this->logActivity

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan!');
    }

    public function editMahasiswa(Mahasiswa $mahasiswa)
    {
        $prodis = Prodi::all(); // Fetch all program studies for the dropdown
        $kelas = Kelas::all(); // Fetch all classes

        return view('admin.mahasiswa.edit', compact('mahasiswa', 'prodis', 'kelas'));
    }

    public function updateMahasiswa(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'nim' => 'required|unique:mahasiswas,nim,'.$mahasiswa->id,
            'nama_lengkap' => 'required',
            'prodi_id' => 'required|exists:prodis,id',
            'jenis_kelamin' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
            'email' => 'required|email|unique:users,email,'.$mahasiswa->user->id, // Validate email for existing user
        ]);

        // Update User data if email is changed
        $user = $mahasiswa->user;
        if ($user && $user->email !== $request->email) {
            $user->email = $request->email;
            $user->save();
        }
        // Update name for user as well
        if ($user && $user->name !== $request->nama_lengkap) {
            $user->name = $request->nama_lengkap;
            $user->save();
        }

        $mahasiswa->update([
            'nim' => $request->nim,
            'nama_lengkap' => $request->nama_lengkap,
            'prodi_id' => $request->prodi_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kelas_id' => $request->kelas_id,
        ]);

        $this->logActivity('Mengupdate mahasiswa: '.$mahasiswa->nama_lengkap, 'Mahasiswa');

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil diupdate.');
    }

    public function destroyMahasiswa(Mahasiswa $mahasiswa)
    {
        $mahasiswa->user()->delete(); // Delete associated user first
        $mahasiswa->delete();

        $this->logActivity('Menghapus mahasiswa: '.$mahasiswa->nama_lengkap, 'Mahasiswa');

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil dihapus.');
    }

    public function detailDosen(Dosen $dosen)
    {
        return view('admin.dosen.show', compact('dosen'));
    }

    public function createDosen()
    {
        $prodis = Prodi::all(); // Fetch all program studies

        return view('admin.dosen.create', compact('prodis'));
    }

    public function storeDosen(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nidn' => 'required|unique:dosens',
            'nama' => 'required',
            'prodi_id' => 'required|exists:prodis,id',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'dosen',
        ]);

        Dosen::create([
            'user_id' => $user->id,
            'nidn' => $request->nidn,
            'nama' => $request->nama,
            'prodi_id' => $request->prodi_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            // 'password' => $request->password, // Ini dihapus karena password ada di tabel users
        ]);

        $this->logActivity('Membuat dosen baru: '.$request->nama, 'Dosen');

        return redirect()->route('admin.dosen.index')->with('success', 'Akun '.$request->nama.' berhasil dibuat!');
    }

    public function editDosen(Dosen $dosen)
    {
        $prodis = Prodi::all(); // Fetch all program studies

        return view('admin.dosen.edit', compact('dosen', 'prodis'));
    }

    public function updateDosen(Request $request, Dosen $dosen)
    {
        $request->validate([
            'nidn' => 'required|unique:dosens,nidn,'.$dosen->id,
            'nama' => 'required',
            'prodi_id' => 'required|exists:prodis,id',
            'jenis_kelamin' => 'required',
            'email' => 'required|email|unique:users,email,'.$dosen->user->id, // Validate email for existing user
        ]);

        // Update User data if email or name is changed
        $user = $dosen->user;
        if ($user) {
            if ($user->email !== $request->email) {
                $user->email = $request->email;
                $user->save();
            }
            if ($user->name !== $request->nama) {
                $user->name = $request->nama;
                $user->save();
            }
        }

        $dosen->update([
            'nidn' => $request->nidn,
            'nama' => $request->nama,
            'prodi_id' => $request->prodi_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            // Jika ada kolom lain yang diupdate di model Dosen, tambahkan di sini
        ]);

        $this->logActivity('Mengupdate dosen: '.$dosen->nama, 'Dosen'); // Menggunakan nama dari model Dosen

        return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil diupdate.');
    }

    public function destroyDosen(Dosen $dosen)
    {
        $dosen->user()->delete(); // Delete associated user first
        $dosen->delete();

        $this->logActivity('Menghapus dosen: '.$dosen->nama, 'Dosen');

        return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil dihapus.');
    }

    public function importDosenForm()
    {
        return view('admin.dosen.import');
    }

    public function importDosen(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new DosenImport, $request->file('file'));

            return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil diimport.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = 'Baris '.$failure->row().': '.implode(', ', $failure->errors());
            }

            return redirect()->back()->with('error', 'Gagal mengimpor data dosen. Ada kesalahan validasi: '.implode('; ', $errors));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data dosen: '.$e->getMessage());
        }
    }

    // Dibawah ini Pengajuan Sidang Methods
    public function daftarPengajuan()
    {
        $pengajuans = Pengajuan::with('mahasiswa')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pengajuan.index', compact('pengajuans'));
    }

    // Dibawah ini Persidangan Methods
    public function daftarSidang(Request $request)
    {
        $query = Sidang::with(['pengajuan.mahasiswa', 'pengajuan.prodi', 'pengajuan.kelas']);

        // Filter functionality
        if ($request->has('filter_jenis') && ! empty($request->filter_jenis)) {
            $filterJenis = $request->filter_jenis;
            $query->whereHas('pengajuan', function ($q) use ($filterJenis) {
                $q->where('jenis_pengajuan', $filterJenis);
            });
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('pengajuan.mahasiswa', function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                })
                    ->orWhereHas('pengajuan', function ($q) use ($search) {
                        $q->where('judul_pengajuan', 'like', "%{$search}%")
                            ->orWhere('jenis_pengajuan', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%");
                    });
            });
        }

        // Sorting functionality
        switch ($request->sort) {
            case 'tanggal_sidang_asc':
                $query->orderBy('tanggal_sidang', 'asc');
                break;
            case 'tanggal_sidang_desc':
                $query->orderBy('tanggal_sidang', 'desc');
                break;
            case 'mahasiswa_asc':
                $query->join('pengajuans', 'sidangs.pengajuan_id', '=', 'pengajuans.id')
                    ->join('mahasiswas', 'pengajuans.mahasiswa_id', '=', 'mahasiswas.id')
                    ->orderBy('mahasiswas.nama_lengkap', 'asc')
                    ->select('sidangs.*'); // Select sidangs.* to avoid column ambiguity
                break;
            case 'mahasiswa_desc':
                $query->join('pengajuans', 'sidangs.pengajuan_id', '=', 'pengajuans.id')
                    ->join('mahasiswas', 'pengajuans.mahasiswa_id', '=', 'mahasiswas.id')
                    ->orderBy('mahasiswas.nama_lengkap', 'desc')
                    ->select('sidangs.*'); // Select sidangs.* to avoid column ambiguity
                break;
            case 'jenis_pengajuan_asc':
                $query->join('pengajuans', 'sidangs.pengajuan_id', '=', 'pengajuans.id')
                    ->orderBy('pengajuans.jenis_pengajuan', 'asc')
                    ->select('sidangs.*');
                break;
            case 'jenis_pengajuan_desc':
                $query->join('pengajuans', 'sidangs.pengajuan_id', '=', 'pengajuans.id')
                    ->orderBy('pengajuans.jenis_pengajuan', 'desc')
                    ->select('sidangs.*');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $sidangs = $query->paginate(10);

        return view('admin.sidang.index', compact('sidangs'));
    }

    public function kalenderSidang()
    {
        $sidangs = Sidang::with('pengajuan.mahasiswa')->get();
        $events = [];

        foreach ($sidangs as $sidang) {
            if ($sidang->tanggal_sidang) {
                $events[] = [
                    'title' => 'Sidang '.$sidang->pengajuan->mahasiswa->nama_lengkap,
                    'start' => $sidang->tanggal_sidang,
                    // Tambahkan data lain yang ingin ditampilkan di kalender
                ];
            }
        }

        return view('admin.sidang.kalender', compact('events'));
    }

    public function detailSidang(Sidang $sidang)
    {
        return view('admin.sidang.show', compact('sidang'));
    }

    // Dibawah ini import mahasiswa method
    public function importMahasiswaForm()
    {
        return view('admin.mahasiswa.import');
    }

    public function importMahasiswa(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new MahasiswaImport, $request->file('file'));

            return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diimport.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = 'Baris '.$failure->row().': '.implode(', ', $failure->errors());
            }

            return redirect()->back()->with('error', 'Gagal mengimpor data mahasiswa. Ada kesalahan validasi: '.implode('; ', $errors));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data mahasiswa: '.$e->getMessage());
        }
    }

    // Dibawah ini export (mahasiswa, Dosen, Sidang) method
    public function exportMahasiswa()
    {
        return Excel::download(new MahasiswaExport, 'data_mahasiswa.xlsx'); // Perbaikan: singular
    }

    public function exportDosen()
    {
        return Excel::download(new DosenExport, 'data_dosen.xlsx'); // Perbaikan: singular
    }

    public function exportSidang()
    {
        try {
            return Excel::download(new SidangExport, 'data_persidangan.xlsx');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Excel Export Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor data sidang: ' . $e->getMessage());
        }
    }

    // Dibawah ini Untuk Log aktivitas
    public function showActivities(Request $request)
    {
        $query = Activity::with('user')->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('activity', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $activities = $query->paginate(10);

        return view('admin.activities.index', compact('activities'));
    }

    // Program Studi Management
    public function indexProdi(Request $request)
    {
        $query = Prodi::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_prodi', 'like', "%{$search}%");
        }

        $prodis = $query->paginate(10);

        return view('admin.prodi.index', compact('prodis'));
    }

    public function createProdi()
    {
        return view('admin.prodi.create');
    }

    public function storeProdi(Request $request)
    {
        $request->validate([
            'nama_prodi' => 'required|unique:prodis,nama_prodi',
        ]);

        Prodi::create(['nama_prodi' => $request->nama_prodi]);

        $this->logActivity('Menambah program studi baru: '.$request->nama_prodi, 'Prodi');

        return redirect()->route('admin.prodi.index')->with('success', 'Program studi berhasil ditambahkan!');
    }

    public function editProdi(Prodi $prodi)
    {
        return view('admin.prodi.edit', compact('prodi'));
    }

    public function updateProdi(Request $request, Prodi $prodi)
    {
        $request->validate([
            'nama_prodi' => 'required|unique:prodis,nama_prodi,'.$prodi->id,
        ]);

        $prodi->update(['nama_prodi' => $request->nama_prodi]);

        $this->logActivity('Mengupdate program studi: '.$prodi->nama_prodi, 'Prodi');

        return redirect()->route('admin.prodi.index')->with('success', 'Program studi berhasil diupdate.');
    }

    public function destroyProdi(Prodi $prodi)
    {
        $prodi->delete();

        $this->logActivity('Menghapus program studi: '.$prodi->nama_prodi, 'Prodi');

        return redirect()->route('admin.prodi.index')->with('success', 'Program studi berhasil dihapus.');
    }
}
