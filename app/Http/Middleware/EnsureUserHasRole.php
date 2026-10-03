<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $allowedValues = array_map(
            fn (string|Role $role) => $role instanceof Role ? $role->value : strtolower((string) $role),
            $roles
        );

        $userRoleValue = $user->role instanceof Role ? $user->role->value : strtolower((string) $user->role);

        if (! in_array($userRoleValue, $allowedValues, true)) {
            abort(403, 'Unauthorized access to this section.');
        }

        return $next($request);
    }
}
