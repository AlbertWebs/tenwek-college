<?php

namespace App\Http\Middleware;

use App\Support\Admin\AdminActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogAdminActivity
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        try {
            AdminActivityLogger::logFromRequest($request, $response);
        } catch (\Throwable) {
            // Never break the response because logging failed.
        }
    }
}
