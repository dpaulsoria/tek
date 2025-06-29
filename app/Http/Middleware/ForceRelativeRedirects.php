<?php
// app/Http/Middleware/ForceRelativeRedirects.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ForceRelativeRedirects
{
    public function handle(Request $request, Closure $next)
    {
        /** @var \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse $response */
        $response = $next($request);

        if ($response instanceof RedirectResponse) {
            $location = $response->headers->get('Location');
            if ($location) {
                // Quita la parte de esquema y host de APP_URL
                $base = config('app.url');
                if (str_starts_with($location, $base)) {
                    $relative = substr($location, strlen($base)) ?: '/';
                } else {
                    // si por algún motivo no coincide con APP_URL,
                    // intenta extraer solo path y query
                    $relative = parse_url($location, PHP_URL_PATH)
                              . (parse_url($location, PHP_URL_QUERY) ? '?' . parse_url($location, PHP_URL_QUERY) : '');
                }
                $response->headers->set('Location', $relative);
            }
        }

        return $response;
    }
}
