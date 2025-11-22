<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsCampaignCreator
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->canManageCampaigns()) {
            abort(403, 'Accès réservé aux créateurs de campagnes.');
        }

        return $next($request);
    }
}
