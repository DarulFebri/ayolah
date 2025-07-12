<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; // Import Activity model

class ProfileController extends Controller
{
    protected function logActivity($description, $subjectType = null)
    {
        Activity::create([
            'user_id' => Auth::id(),
            'activity' => $description,
            'subject_type' => $subjectType,
            'subject_id' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
    }

    public function showChangePasswordForm()
    {
        return view('admin.profile.change-password');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        $this->logActivity('Mengubah kata sandi admin', 'Admin');

        return redirect()->back()->with('success', 'Kata sandi berhasil diubah!');
    }
}
