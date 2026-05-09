<?php

namespace App\Http\Middleware;

use App\Models\School;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanManageSoc
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $soc = School::query()->where('slug', 'soc')->firstOrFail();

        if (! $user->managesSchool($soc)) {
            abort(403);
        }

        $request->attributes->set('socSchool', $soc);

        return $next($request);
    }
}
