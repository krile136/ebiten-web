<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\OneTimeToken;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'user_id' => ['required'],
            'one_time_token' => ['required'],
        ]);

        $user_id = $credentials['user_id'];
        $plain_text_token = $credentials['one_time_token'];

        $user = User::query()
        ->findOrFail($user_id);

        $one_time_token = OneTimeToken::query()
        ->where('user_id', $user->id)
        ->where('token', hash('sha256', $plain_text_token))
        ->where('is_used', false)
        ->get()
        ->first();

        if($one_time_token === null) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $one_time_token->update([
            'is_used' => true,
        ]);

        $user->personalAccessTokens()->delete();

        $token = $user->createToken($user->name);

        session(['token' => $token->plainTextToken]);
        logger()->debug(sprintf("plain text token: %s", $token->plainTextToken));
        return response()->json(['api_token' => $token->plainTextToken], 200);
    } 
}