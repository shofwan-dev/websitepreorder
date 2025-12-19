<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Jika tidak ada permission yang ditentukan, izinkan semua
        if (empty($permissions)) {
            return $next($request);
        }
        
        // Cek apakah user memiliki salah satu permission
        foreach ($permissions as $permission) {
            // Logika sederhana: jika user adalah admin, izinkan semua
            if ($user->role === 'admin' || $user->role === 'super_admin') {
                return $next($request);
            }
            
            // Untuk permission yang lebih kompleks, bisa ditambahkan logika di sini
            // Misalnya: $user->hasPermission($permission)
        }
        
        abort(403, 'Akses ditolak. Anda tidak memiliki permission yang diperlukan.');
    }
}