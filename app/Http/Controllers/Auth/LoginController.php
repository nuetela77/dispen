<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (auth()->check()) {
            return $this->redirectByRole();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
                $request->session()->regenerate();
                try {
                    ActivityLog::catat(auth()->id(), null, 'login', 'User berhasil login.');
                } catch (\Throwable $e) {}
                return $this->redirectByRole();
            }
        } catch (\Throwable $e) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Koneksi database bermasalah: ' . $e->getMessage()]);
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        ActivityLog::catat(auth()->id(), null, 'logout', 'User logout.');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }

    private function redirectByRole()
    {
        return match (auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'guru_petugas' => redirect()->route('guru.dashboard'),
            default => redirect()->route('siswa.dashboard'),
        };
    }
}