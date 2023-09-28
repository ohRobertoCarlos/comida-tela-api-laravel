<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Routing\Middleware\ValidateSignature as Middleware;

class ValidateSignature extends Middleware
{
    /**
     * The names of the query string parameters that should be ignored.
     *
     * @var array<int, string>
     */
    protected $except = [
        // 'fbclid',
        // 'utm_campaign',
        // 'utm_content',
        // 'utm_medium',
        // 'utm_source',
        // 'utm_term',
    ];

    public function handle($request, \Closure $next, ...$args)
    {
        [$relative, $ignore] = $this->parseArguments($args);

        if ($request->hasValidSignatureWhileIgnoring($ignore, ! $relative)) {
            return $next($request);
        }

        return redirect(env('APP_CLIENT_URL') . '/email/verify/error');
    }
}
