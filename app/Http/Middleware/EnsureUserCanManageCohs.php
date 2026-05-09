<?php

namespace App\Http\Middleware;

use App\Models\School;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanManageCohs
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $cohs = School::query()->where('slug', 'cohs')->firstOrFail();

        if (! $user->managesSchool($cohs)) {
            abort(403);
        }

        $request->attributes->set('cohsSchool', $cohs);

        return $next($request);
    }
}
