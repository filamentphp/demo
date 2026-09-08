<?php

namespace App\LiveDemo;

use Closure;
use Illuminate\Http\Request;

final class SetUpSelection
{
    public function handle(Request $request, Closure $next): mixed
    {
        Selection::current();

        return $next($request);
    }
}
