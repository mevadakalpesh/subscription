<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsHandle
{
  /**
  * Handle an incoming request.
  *
  * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
  */
  public function handle(Request $request, Closure $next): Response
  {
    $response = $next($request);

    $headers = [
      'Access-Control-Allow-Origin' => '*',
      'Access-Control-Allow-Methods' => 'POST,OPTIONS',
      //'Access-Control-Max-Age'       => 604800,
      'Access-Control-Allow-Headers' => 'X-Requested-With, Origin, X-Csrftoken, Accept',
    ];
    return $response->withHeaders($headers);
  }
}