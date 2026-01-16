<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdmin
{
    /**
     * Handle an incoming request.
     * Only allows superadmins to access admin routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        if (!auth()->user()->isSuperAdmin()) {
            // If user is a campaign creator, redirect them to their dashboard
            if (auth()->user()->isCampaignCreator()) {
                return redirect()->route('creator.dashboard')
                    ->with('error', 'Accès réservé aux super administrateurs. Vous avez été redirigé vers votre espace créateur.');
            }
            
            abort(403, 'Accès réservé aux super administrateurs.');
        }

        return $next($request);
    }
}
