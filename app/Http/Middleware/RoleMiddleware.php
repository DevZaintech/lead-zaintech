<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!in_array($user->ROLE, $roles)) {
            // Arahkan ke dashboard sesuai role user
            switch ($user->ROLE) {
                case 'admin':
                    return redirect()->route('dashboard.admin');
                case 'gate':
                    return redirect()->route('dashboard.gate');
                case 'sales':
                    return redirect()->route('dashboard.sales');
                default:
                    return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
