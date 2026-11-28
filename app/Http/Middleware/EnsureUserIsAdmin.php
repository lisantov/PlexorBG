<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null || ! $user->isAdmin()) {
            if ($request->expectsJson()) {
                abort(403);
            }

            Inertia::flash('toast', [
                'type' => 'error',
                'message' => 'Нет доступа к админ-панели.',
            ]);

            return redirect()->route('home');
        }

        return $next($request);
    }
}
