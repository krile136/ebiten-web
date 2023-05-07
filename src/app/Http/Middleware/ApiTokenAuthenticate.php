<?php

namespace App\Http\Middleware;

use App\Models\PersonalAccessToken;
use Closure;
use Illuminate\Http\Request;

class ApiTokenAuthenticate
{
    /**
     * ApiTokenの認証をします。
     * vendor/laravel/sanctum/src/PersonalAccessToken::findTokenを参考に作っています
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  Closure(): void  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        logger()->info(sprintf('header token: %s', $token));
        if ($token === null) {
            logger()->info('API Tokenがありません。');

            return response()->json(['message' => 'api token is require'], 401);
        }

        if (strpos($token, '|') === false) {
            logger()->info('パイプ(|)を含まない不正なトークンです');

            return response()->json(['message' => 'api token is require'], 401);
        }

        [$id, $token] = explode('|', $token, 2);

        $personal_access_token = PersonalAccessToken::query()
                        ->where('id', $id)
                        ->first();

        if ($personal_access_token === null) {
            logger()->info(sprintf('idが不正な値です。id:%d', $id));

            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        if (! hash_equals($personal_access_token->token, hash('sha256', $token))) {
            logger()->info(sprintf('API Tokenが不正な値です。token:%s', $token));

            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        return $next($request);
    }
}
