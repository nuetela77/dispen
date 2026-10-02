<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_siswa' => User::where('role', 'siswa')->count(),
            'total_guru' => User::where('role', 'guru_petugas')->count(),
            'total_admin' => User::where('role', 'admin')->count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }

    public function users(Request $request)
    {
        $query = User::with('siswa');
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
        }
        $users = $query->orderBy('name')->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,guru_petugas,siswa',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->role === 'siswa') {
            Siswa::create([
                'user_id' => $user->id,
                'nama' => $request->name,
                'kelas' => $request->kelas ?? 'X',
                'jurusan' => $request->jurusan ?? 'Umum',
                'nomor_identitas' => $request->nis,
            ]);
        }

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }
}
