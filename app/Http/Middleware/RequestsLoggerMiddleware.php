<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Request;

class RequestsLoggerMiddleware
{
    /**
     * Inputs que não devem ser registrados
     *
     * @var array
     */
    private $dontFlash = [
        'password',
        'password_confirmation'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        if ($request->method() !== 'OPTIONS' && $response->getStatusCode() !== 200 && $response->getStatusCode() !== 201) {
            $endTime = microtime(true);

            $dataToLog = [
                'duration'           => number_format($endTime - LUMEN_START, 3),
                'ip_address'         => $request->header('x-real-ip') ? $request->header('x-real-ip') : $request->ip(),
                'url'                => $request->fullUrl(),
                'method'             => $request->method(),
                'headers'            => mb_strcut(json_encode($request->headers->all()), 0, 65535),
                'input'              => mb_strcut(json_encode($request->except($this->dontFlash)), 0, 65535),
                'output'             => mb_strcut($response->getContent(), 0, 65535),
                'http_response_code' => $response->getStatusCode(),
                'user_id'            => Auth::id()
            ];

            Request::create($dataToLog);
        }
    }
}
