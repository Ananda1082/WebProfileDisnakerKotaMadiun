<?php
// app/Http/Controllers/Auth/LoginController.php
protected function authenticated(Request $request, $user)
{
    if (is_null($user->approved_at)) {
        auth()->logout();
        return back()->withErrors(['email' => 'Akun Anda belum disetujui admin.']);
    }
}
