<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Dosen;
use App\Models\Pengajuan;
use App\Models\PengajuanStatusHistory;
use App\Models\Sidang;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth; // Import this!
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // Import the new model

class DosenController extends Controller
{
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

    public function loginForm()
    {
        return view('dosen.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'dosen';

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dosen.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function dashboard()
    {
        $user = Auth::user();
        $dosen = $user->dosen;

        if (! $dosen) {
            Auth::logout();

            return redirect()->route('dosen.login')->with('error', 'Profil dosen tidak ditemukan.');
        }

        $dosenLoginId = $dosen->id;

        $unreadNotifications = $user->unreadNotifications;

        // Ambil sidang di mana dosen ini terlibat dan statusnya masih 'pending'
        $sidangInvitations = Sidang::where(function ($query) use ($dosenLoginId) {
            $query->where('sekretaris_sidang_dosen_id', $dosenLoginId)
                ->where('persetujuan_sekretaris_sidang', 'pending')
                ->whereHas('pengajuan', function ($subQuery) {
                    $subQuery->where('status', '!=', 'draft');
                });
        })->orWhere(function ($query) use ($dosenLoginId) {
            $query->where('anggota1_sidang_dosen_id', $dosenLoginId)
                ->where('persetujuan_anggota1_sidang', 'pending')
                ->whereHas('pengajuan', function ($subQuery) {
                    $subQuery->where('status', '!=', 'draft');
                });
        })->orWhere(function ($query) use ($dosenLoginId) {
            $query->where('anggota2_sidang_dosen_id', $dosenLoginId)
                ->where('persetujuan_anggota2_sidang', 'pending')
                ->whereHas('pengajuan', function ($subQuery) {
                    $subQuery->where('status', '!=', 'draft');
                });
        })->orWhere(function ($query) use ($dosenLoginId) {
            $query->where('dosen_pembimbing_id', $dosenLoginId)
                ->where('persetujuan_dosen_pembimbing', 'pending')
                ->whereHas('pengajuan', function ($subQuery) {
                    $subQuery->where('status', '!=', 'draft');
                });
        })->orWhere(function ($query) use ($dosenLoginId) {
            $query->where('dosen_penguji1_id', $dosenLoginId)
                ->where('persetujuan_dosen_penguji1', 'pending')
                ->whereHas('pengajuan', function ($subQuery) {
                    $subQuery->where('status', '!=', 'draft');
                });
        })
            ->with([
                'pengajuan.mahasiswa',
                'ketuaSidang',
                'sekretarisSidang',
                'anggota1Sidang',
                'anggota2Sidang',
                'dosenPembimbing',
                'dosenPenguji1',
            ])
            ->get();

        foreach ($sidangInvitations as $sidangInvitation) {
            Log::info('Sidang Invitation Pengajuan Status: '.$sidangInvitation->pengajuan->status.' for Pengajuan ID: '.$sidangInvitation->pengajuan->id);
        }

        // --- CORRECTED QUERIES FOR APPROVED AND REJECTED SIDANGS ---
        // Helper function to check if a specific role for the logged-in dosen is 'setuju' or 'tolak'
        $getSidangsByResponse = function ($responseType) use ($dosenLoginId) {
            return Sidang::where(function ($query) use ($dosenLoginId, $responseType) {
                $query->where(function ($q) use ($dosenLoginId, $responseType) {
                    $q->where('sekretaris_sidang_dosen_id', $dosenLoginId)
                        ->where('persetujuan_sekretaris_sidang', $responseType);
                })
                    ->orWhere(function ($q) use ($dosenLoginId, $responseType) {
                        $q->where('anggota1_sidang_dosen_id', $dosenLoginId)
                            ->where('persetujuan_anggota1_sidang', $responseType);
                    })
                    ->orWhere(function ($q) use ($dosenLoginId, $responseType) {
                        $q->where('anggota2_sidang_dosen_id', $dosenLoginId)
                            ->where('persetujuan_anggota2_sidang', $responseType);
                    })
                    ->orWhere(function ($q) use ($dosenLoginId, $responseType) {
                        $q->where('dosen_pembimbing_id', $dosenLoginId)
                            ->where('persetujuan_dosen_pembimbing', $responseType);
                    })
                    ->orWhere(function ($q) use ($dosenLoginId, $responseType) {
                        $q->where('dosen_penguji1_id', $dosenLoginId)
                            ->where('persetujuan_dosen_penguji1', $responseType);
                    });
            })
                ->with([
                    'pengajuan.mahasiswa',
                    'ketuaSidang',
                    'sekretarisSidang',
                    'anggota1Sidang',
                    'anggota2Sidang',
                    'dosenPembimbing',
                    'dosenPenguji1',
                ])
                ->get();
        };

        $approvedSidangs = $getSidangsByResponse('setuju');
        $rejectedSidangs = $getSidangsByResponse('tolak');

        // Ambil sidang yang sudah disetujui dan akan datang
        $upcomingSidangs = Sidang::where(function ($query) use ($dosenLoginId) {
            $query->where(function ($q) use ($dosenLoginId) {
                $q->where('sekretaris_sidang_dosen_id', $dosenLoginId)
                    ->where('persetujuan_sekretaris_sidang', 'setuju');
            })
                ->orWhere(function ($q) use ($dosenLoginId) {
                    $q->where('anggota1_sidang_dosen_id', $dosenLoginId)
                        ->where('persetujuan_anggota1_sidang', 'setuju');
                })
                ->orWhere(function ($q) use ($dosenLoginId) {
                    $q->where('anggota2_sidang_dosen_id', $dosenLoginId)
                        ->where('persetujuan_anggota2_sidang', 'setuju');
                })
                ->orWhere(function ($q) use ($dosenLoginId) {
                    $q->where('dosen_pembimbing_id', $dosenLoginId)
                        ->where('persetujuan_dosen_pembimbing', 'setuju');
                })
                ->orWhere(function ($q) use ($dosenLoginId) {
                    $q->where('dosen_penguji1_id', $dosenLoginId)
                        ->where('persetujuan_dosen_penguji1', 'setuju');
                });
        })
            ->where('tanggal_waktu_sidang', '>=', now()) // Filter for future dates
            ->with([
                'pengajuan.mahasiswa',
                'ketuaSidang',
                'sekretarisSidang',
                'anggota1Sidang',
                'anggota2Sidang',
                'dosenPembimbing',
                'dosenPenguji1',
            ])
            ->orderBy('tanggal_waktu_sidang', 'asc') // Order by date
            ->get();
        // --- END CORRECTED QUERIES ---

        // Ambil sidang di mana dosen ini terlibat dan sudah berlalu
        $pastSidangs = Sidang::where(function ($query) use ($dosenLoginId) {
            $query->where('dosen_pembimbing_id', $dosenLoginId)
                ->orWhere('dosen_penguji1_id', $dosenLoginId)
                ->orWhere('dosen_penguji2_id', $dosenLoginId)
                ->orWhere('ketua_sidang_dosen_id', $dosenLoginId)
                ->orWhere('sekretaris_sidang_dosen_id', $dosenLoginId)
                ->orWhere('anggota1_sidang_dosen_id', $dosenLoginId)
                ->orWhere('anggota2_sidang_dosen_id', $dosenLoginId);
        })
            ->where('tanggal_waktu_sidang', '<', now()) // Filter for past dates
            ->with([
                'pengajuan.mahasiswa',
                'ketuaSidang',
                'sekretarisSidang',
                'anggota1Sidang',
                'anggota2Sidang',
                'dosenPembimbing',
                'dosenPenguji1',
                'dosenPenguji2',
            ])
            ->orderBy('tanggal_waktu_sidang', 'desc') // Order by date, newest first
            ->get();

        return view('dosen.dashboard', compact('unreadNotifications', 'sidangInvitations', 'approvedSidangs', 'rejectedSidangs', 'upcomingSidangs', 'pastSidangs'));
    }

    public function editProfileForm()
    {
        $dosen = Auth::user()->dosen; // Assuming 'dosen' relationship exists on User model
        if (! $dosen) {
            return redirect()->route('dosen.dashboard')->with('error', 'Profil dosen tidak ditemukan.');
        }

        return view('dosen.profile.edit', compact('dosen'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $dosen = $user->dosen;

        if (! $dosen) {
            return redirect()->route('dosen.dashboard')->with('error', 'Profil dosen tidak ditemukan.');
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nidn' => 'nullable|string|max:255|unique:dosens,nidn,'.$dosen->id,
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id, // Added email validation
            'nomor_hp' => 'nullable|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        $dosen->nama = $request->nama_lengkap;
        $dosen->nidn = $request->nidn;
        $dosen->nomor_hp = $request->nomor_hp;

        // Update user's email
        if ($user->email !== $request->email) {
            $user->email = $request->email;
            $user->save();
        }

        if ($request->hasFile('foto_profil')) {
            // Delete old profile picture if exists
            if ($dosen->foto_profil && Storage::exists($dosen->foto_profil)) {
                Storage::delete($dosen->foto_profil);
            }
            $path = $request->file('foto_profil')->store('profile_photos/dosen', 'public');
            $dosen->foto_profil = str_replace('public/', '', $path);
        }

        // $dosen->profile_edited_at = now(); // Update timestamp
        $dosen->save();

        // Update user's name if it's different
        if ($user->name !== $request->nama_lengkap) {
            $user->name = $request->nama_lengkap;
            $user->save();
        }

        return redirect()->route('dosen.profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    public function changePasswordForm()
    {
        return view('dosen.profile.change_password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->route('dosen.profile.edit')->with('success', 'Kata sandi berhasil diubah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('dosen.login');
    }

    public function daftarPengajuan()
    {
        $pengajuans = Pengajuan::with([
            'mahasiswa',
            'sidang.sekretarisSidang',
            'sidang.anggota1Sidang',
            'sidang.anggota2Sidang',
        ])->get();

        return view('dosen.pengajuan.index', compact('pengajuans'));
    }

    public function detailPengajuan(Pengajuan $pengajuan)
    {
        $pengajuan->load([
            'mahasiswa',
            'dokumens',
            'sidang.ketuaSidang',
            'sidang.sekretarisSidang',
            'sidang.anggota1Sidang',
            'sidang.anggota2Sidang',
        ]);

        return view('dosen.pengajuan.show', compact('pengajuan'));
    }

    // Method untuk menandai notifikasi sudah dibaca
    public function markNotificationAsRead(DatabaseNotification $notification)
    {
        if (Auth::id() !== $notification->notifiable_id) {
            abort(403, 'Unauthorized action.');
        }
        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    protected function checkAndSetPengajuanStatus(Sidang $sidang)
    {
        $allDosenResponded = true;
        $allDosenAgreed = true;

        $rolesToCheck = [
            'ketua_sidang' => $sidang->ketua_sidang_dosen_id,
            'sekretaris_sidang' => $sidang->sekretaris_sidang_dosen_id,
            'anggota1_sidang' => $sidang->anggota1_sidang_dosen_id,
            'anggota2_sidang' => $sidang->anggota2_sidang_dosen_id, // ini bisa null
            'dosen_pembimbing' => $sidang->dosen_pembimbing_id,
            'dosen_penguji1' => $sidang->dosen_penguji1_id, // pembimbing 2
        ];

        foreach ($rolesToCheck as $role => $dosenId) {
            if ($dosenId !== null) { // Hanya cek jika peran dosen ini diisi
                $persetujuanKolom = 'persetujuan_'.$role;
                if ($sidang->$persetujuanKolom === 'pending') {
                    $allDosenResponded = false;
                    break;
                }
                if ($sidang->$persetujuanKolom === 'tolak') {
                    $allDosenAgreed = false;
                    break;
                }
            }
        }

        if ($allDosenResponded) {
            $oldStatus = $sidang->pengajuan->status;
            $newStatus = '';
            $notes = '';

            if ($allDosenAgreed) {
                $newStatus = 'dosen_menyetujui';
                $notes = 'Semua dosen yang ditunjuk telah menyetujui jadwal sidang.';
            } else {
                $newStatus = 'dosen_menolak_jadwal';
                $notes = 'Beberapa dosen menolak jadwal sidang.';
            }

            $sidang->pengajuan->update(['status' => $newStatus]);
            $this->logPengajuanStatusChange($sidang->pengajuan, $oldStatus, $newStatus, $notes);
        }
    }

    public function pengajuanSaya()
    {
        $user = Auth::user();

        if (! $user || ! $user->dosen) {
            return redirect()->route('dosen.dashboard')->with('error', 'Akses ditolak. Anda tidak terdaftar sebagai dosen.');
        }

        $dosenId = Auth::user()->dosen->id;

        $pengajuansInvolved = Pengajuan::whereHas('sidang', function ($query) use ($dosenId) {
            $query->where('dosen_pembimbing_id', $dosenId)
                ->orWhere('dosen_penguji1_id', $dosenId)
                ->orWhere('dosen_penguji2_id', $dosenId)
                ->orWhere('ketua_sidang_dosen_id', $dosenId)
                ->orWhere('sekretaris_sidang_dosen_id', $dosenId)
                ->orWhere('anggota1_sidang_dosen_id', $dosenId)
                ->orWhere('anggota2_sidang_dosen_id', $dosenId);
        })
            ->with([
                'mahasiswa',
                'sidang.dosenPembimbing',
                'sidang.dosenPenguji1',
                'sidang.dosenPenguji2',
                'sidang.ketuaSidang',
                'sidang.sekretarisSidang',
                'sidang.anggota1Sidang',
                'sidang.anggota2Sidang',
            ])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('dosen.pengajuan.pengajuan_saya', compact('pengajuansInvolved'));
    }

    public function setujuiDokumen(Dokumen $dokumen)
    {
        $oldStatus = $dokumen->status;
        $newStatus = 'disetujui';
        $dokumen->update(['status' => $newStatus]);
        // Log the status change for the associated Pengajuan
        if ($dokumen->pengajuan) {
            $this->logPengajuanStatusChange($dokumen->pengajuan, $oldStatus, $newStatus, 'Dokumen '.$dokumen->nama_file.' disetujui oleh Dosen.');
        }

        return redirect()->back()->with('success', 'Dokumen berhasil disetujui.');
    }

    public function tolakDokumen(Dokumen $dokumen)
    {
        $oldStatus = $dokumen->status;
        $newStatus = 'ditolak';
        $dokumen->update(['status' => $newStatus]);
        // Log the status change for the associated Pengajuan
        if ($dokumen->pengajuan) {
            $this->logPengajuanStatusChange($dokumen->pengajuan, $oldStatus, $newStatus, 'Dokumen '.$dokumen->nama_file.' ditolak oleh Dosen.');
        }

        return redirect()->back()->with('success', 'Dokumen berhasil ditolak.');
    }

    public function formJadwalSidang(Pengajuan $pengajuan)
    {
        return view('dosen.jadwal.create', compact('pengajuan'));
    }

    public function simpanJadwalSidang(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([
            'tanggal_waktu_sidang' => 'required|date',
            'ruangan_sidang' => 'required|string|max:255',
        ]);

        $sidang = $pengajuan->sidang ?? new Sidang;
        $sidang->pengajuan_id = $pengajuan->id;
        $sidang->tanggal_waktu_sidang = $request->tanggal_waktu_sidang;
        $sidang->ruangan_sidang = $request->ruangan_sidang;
        $sidang->save();

        return redirect()->route('dosen.pengajuan.show', $pengajuan->id)->with('success', 'Jadwal sidang berhasil dibuat.');
    }

    public function detailJadwalSidang(Sidang $sidang)
    {
        $sidang->load([
            'pengajuan.mahasiswa',
            'ketuaSidang',
            'sekretarisSidang',
            'anggota1Sidang',
            'anggota2Sidang',
            'dosenPembimbing',
            'dosenPenguji1',
            'dosenPenguji2',
        ]);

        return view('dosen.jadwal.show', compact('sidang'));
    }

    public function unduhLaporan(Sidang $sidang)
    {
        $laporan = Dokumen::where('pengajuan_id', $sidang->pengajuan_id)
            ->where('jenis_dokumen', 'Laporan TA')
            ->first();

        if (! $laporan || ! Storage::exists($laporan->path_file)) {
            abort(404, 'Laporan Tugas Akhir tidak ditemukan atau file tidak ada.');
        }

        return Storage::download($laporan->path_file, $laporan->nama_file);
    }

    public function formNilaiSidang(Sidang $sidang)
    {
        $dosenId = Auth::user()->dosen->id;
        if (! in_array($dosenId, [
            $sidang->dosen_pembimbing_id,
            $sidang->dosen_penguji1_id,
            $sidang->dosen_penguji2_id,
        ])) {
            abort(403, 'Anda tidak berhak memberikan nilai pada sidang ini.');
        }

        return view('dosen.sidang.nilai.edit', compact('sidang'));
    }

    public function simpanNilaiSidang(Request $request, Sidang $sidang)
    {
        $request->validate([
            'nilai_sidang' => 'required|numeric|min:0|max:100',
            'catatan_sidang' => 'nullable|string',
        ]);

        $sidang->update([
            'nilai_sidang' => $request->nilai_sidang,
            'catatan_sidang' => $request->catatan_sidang,
            'hasil_sidang' => ($request->nilai_sidang >= 60) ? 'Lulus' : 'Tidak Lulus',
        ]);

        return redirect()->route('dosen.sidang.nilai.edit', $sidang->id)->with('success', 'Nilai sidang berhasil disimpan.');
    }

    public function formResponSidang(Sidang $sidang)
    {
        $dosen = Auth::user()->dosen;
        $dosenLoginId = $dosen->id;

        // Determine if the logged-in dosen is involved and still has a pending response
        $isPending = false;

        if ($sidang->sekretaris_sidang_dosen_id === $dosenLoginId && $sidang->persetujuan_sekretaris_sidang === 'pending') {
            $isPending = true;
        }
        if ($sidang->anggota1_sidang_dosen_id === $dosenLoginId && $sidang->persetujuan_anggota1_sidang === 'pending') {
            $isPending = true;
        }
        if ($sidang->anggota2_sidang_dosen_id === $dosenLoginId && $sidang->persetujuan_anggota2_sidang === 'pending') {
            $isPending = true;
        }
        if ($sidang->dosen_pembimbing_id === $dosenLoginId && $sidang->persetujuan_dosen_pembimbing === 'pending') {
            $isPending = true;
        }
        if ($sidang->dosen_penguji1_id === $dosenLoginId && $sidang->persetujuan_dosen_penguji1 === 'pending') {
            $isPending = true;
        }

        if ($isPending) {
            $sidang->load('pengajuan.mahasiswa.prodi', 'pengajuan.mahasiswa.kelas', 'pengajuan.mahasiswa.user', 'ketuaSidang', 'sekretarisSidang', 'anggota1Sidang', 'anggota2Sidang', 'dosenPembimbing', 'dosenPenguji1');

            return view('dosen.respon_sidang', compact('sidang', 'dosen'));
        }

        // If dosen is not involved with a pending response, they are redirected.
        // We can optionally check if they were involved at all to give a more specific message.
        $wasInvolved = false;
        if (
            $sidang->ketua_sidang_dosen_id === $dosenLoginId ||
            $sidang->sekretaris_sidang_dosen_id === $dosenLoginId ||
            $sidang->anggota1_sidang_dosen_id === $dosenLoginId ||
            $sidang->anggota2_sidang_dosen_id === $dosenLoginId ||
            $sidang->dosen_pembimbing_id === $dosenLoginId ||
            $sidang->dosen_penguji1_id === $dosenLoginId
        ) {
            $wasInvolved = true;
        }

        if ($wasInvolved) {
            return redirect()->route('dosen.dashboard')->with('info', 'Anda sudah merespon undangan sidang ini.');
        } else {
            return redirect()->route('dosen.dashboard')->with('error', 'Anda tidak memiliki akses ke undangan sidang ini.');
        }
    }

    public function submitResponSidang(Request $request, Sidang $sidang)
    {
        $request->validate([
            'respon' => 'required|in:setuju,tolak',
            'catatan' => 'nullable|string|max:500',
        ]);

        $dosen = Auth::user()->dosen;
        $respon = $request->respon;
        $catatan = $request->catatan;
        $peranDosen = null;

        // Load the pengajuan relationship to check jenis_pengajuan
        $sidang->load('pengajuan');

        if ($sidang->pengajuan->jenis_pengajuan === 'pkl' && $sidang->dosen_pembimbing_id === $dosen->id && $sidang->persetujuan_dosen_pembimbing === 'pending') {
            // For PKL, Dosen Pembimbing 1 is also Ketua Sidang, but we only need one approval for 'dosen_pembimbing'
            $sidang->persetujuan_dosen_pembimbing = $respon;
            if ($respon === 'tolak') {
                $sidang->alasan_penolakan_dosen_pembimbing = $catatan;
            }
            $peranDosen = 'Dosen Pembimbing 1 (Ketua Sidang)';

        } elseif ($sidang->sekretaris_sidang_dosen_id === $dosen->id && $sidang->persetujuan_sekretaris_sidang === 'pending') {
            $sidang->persetujuan_sekretaris_sidang = $respon;
            if ($respon === 'tolak') {
                $sidang->alasan_penolakan_sekretaris_sidang = $catatan;
            }
            $peranDosen = 'Sekretaris Sidang';
        } elseif ($sidang->anggota1_sidang_dosen_id === $dosen->id && $sidang->persetujuan_anggota1_sidang === 'pending') {
            $sidang->persetujuan_anggota1_sidang = $respon;
            if ($respon === 'tolak') {
                $sidang->alasan_penolakan_anggota1_sidang = $catatan;
            }
            $peranDosen = 'Anggota Sidang 1';
        } elseif ($sidang->anggota2_sidang_dosen_id === $dosen->id && $sidang->persetujuan_anggota2_sidang === 'pending') {
            $sidang->persetujuan_anggota2_sidang = $respon;
            if ($respon === 'tolak') {
                $sidang->alasan_penolakan_anggota2_sidang = $catatan;
            }
            $peranDosen = 'Anggota Sidang 2';
        } elseif ($sidang->dosen_pembimbing_id === $dosen->id && $sidang->persetujuan_dosen_pembimbing === 'pending') {
            $sidang->persetujuan_dosen_pembimbing = $respon;
            if ($respon === 'tolak') {
                $sidang->alasan_penolakan_dosen_pembimbing = $catatan;
            }
            $peranDosen = 'Dosen Pembimbing 1';
        } elseif ($sidang->dosen_penguji1_id === $dosen->id && $sidang->persetujuan_dosen_penguji1 === 'pending') {
            $sidang->persetujuan_dosen_penguji1 = $respon;
            if ($respon === 'tolak') {
                $sidang->alasan_penolakan_dosen_penguji1 = $catatan;
            }
            $peranDosen = 'Dosen Pembimbing 2';
        } else {
            return back()->with('error', 'Anda tidak dapat merespon undangan ini lagi atau tidak terkait.');
        }

        $sidang->save();

        // Log individual dosen response
        $oldPengajuanStatus = $sidang->pengajuan->status; // Get current pengajuan status
        $newPengajuanStatus = $sidang->pengajuan->status; // Status of pengajuan doesn't change here, only individual dosen approval
        $notes = "Dosen {$dosen->nama} sebagai {$peranDosen} telah ".($respon === 'setuju' ? 'menyetujui' : 'menolak').' undangan sidang.';
        $this->logPengajuanStatusChange($sidang->pengajuan, $oldPengajuanStatus, $newPengajuanStatus, $notes);

        // After saving the individual dosen's response, check if all dosen have responded
        $this->checkAndSetPengajuanStatus($sidang);

        return redirect()->route('dosen.dashboard')->with('success', "Respon Anda sebagai {$peranDosen} ($respon) berhasil disimpan.");
    }
}
