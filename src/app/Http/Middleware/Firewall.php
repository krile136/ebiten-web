<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\IpUtils;

class Firewall
{
    private const ALLOWED_IPS = [
        '172.0.0.1'
    ];

     /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle(Request $request, Closure $next)
    {
        logger()->debug($request->getHost());
        if (config('app.env') === 'local' || config('app.env') === 'ngrok' | $this->isAllowedIp($request->ip())) {
            return $next($request);
        }

        throw new AuthorizationException(sprintf('Access denied from %s', $request->ip()));
    }

    /**
     * @param string $ip
     * @return bool
     */
    private function isAllowedIp(string $ip): bool
    {
        return IpUtils::checkIp($ip, self::ALLOWED_IPS);
    }   
}
