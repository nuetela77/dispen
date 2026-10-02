<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) return redirect()->route('login');
        if (!in_array(auth()->user()->role, $roles)) {
            $role = auth()->user()->role;
            $route = $role === 'admin' ? 'admin.dashboard' : ($role === 'guru_petugas' ? 'guru.dashboard' : 'siswa.dashboard');
            return redirect()->route($route)->with('error', 'Anda tidak memiliki akses.');
        }
        return $next($request);
    }
}