<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OneTimeToken;
use App\Models\User;
use App\Traits\Aes;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * アプリの認証を行うコントローラー
 *
 **/
class AuthController extends Controller
{
    use Aes;

    /**
     * Authenticate method
     *
     * @param  Request  $request リクエストデータ
     * @return JsonResponse JsonResponseを返す
     **/
    public function authenticate(Request $request): JsonResponse
    {
        $credentials = $request->validate(
            [
                'user_id' => ['required'],
                'one_time_token' => ['required'],
            ]
        );

        $user_id = $credentials['user_id'];
        $plain_text_token = $credentials['one_time_token'];

        Auth::loginUsingId($user_id);

        try {
            DB::beginTransaction();

            /* @var User $user */
            $user = User::query()
            ->findOrFail($user_id);

            $one_time_token = OneTimeToken::query()
                ->where('user_id', $user->id)
                ->where('token', hash('sha256', $plain_text_token))
                ->where('is_used', false)
                ->get()
                ->first();

            if ($one_time_token === null) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }

            $one_time_token->update(
                [
                    'is_used' => true,
                ]
            );

            $user->personalAccessTokens()->delete();

            $token = $user->createToken($user->name);

            session(['token' => $token->plainTextToken]);
            logger()->debug(sprintf('token: %s', $token->accessToken));
            logger()->debug(sprintf('plain text token: %s', $token->plainTextToken));

            $encrypt_data = ['api_token' => $token->plainTextToken];
            $data = $this->encrypt($encrypt_data);

            DB::commit();
        } catch (Exception $e) {
            logger()->error($e);
            DB::rollBack();
        }

        return response()->json(['data' => $data], 200);
    }
}
