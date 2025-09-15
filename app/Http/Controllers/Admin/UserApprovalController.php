<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserApprovalController extends Controller
{
    public function index(): View
    {
        $pending = User::pending()
            ->where('is_admin', false)
            ->latest()
            ->paginate(15);

        return view('admin.users.pending', compact('pending'));
    }

    public function approve(User $user): RedirectResponse
    {
        abort_if($user->is_admin, 403);

        $user->is_approved = true;
        $user->save();

        // (Opsional) kirim email notifikasi ke user di sini

        return back()->with('status', "User {$user->email} telah disetujui.");
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->is_admin, 403);

        $email = $user->email;
        $user->delete();

        return back()->with('status', "Pendaftaran {$email} ditolak & akun dihapus.");
    }
}
