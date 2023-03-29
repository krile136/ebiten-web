<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Score;
use App\Models\User;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    public function storeOrUpdate(Request $request)
    {
        $credentials = $request->validate(
            [
                'user_id' => ['required'],
                'score' => ['required'],
            ]
        );

        $user_id = $credentials['user_id'];
        $current_score = $credentials['score'];

        /** @var User $user */
        $user = User::query()
                ->where('id', $user_id)
                ->first();

        if ($user === null) {
            // createする
            $score = new Score();
            $previous_score = 0;
            $score->fill(['user_id' => $user_id, 'score' => $current_score])->save();
        } else {
            // updateする
            /** @var Score $score */
            $score = $user->score;
            $previous_score = $score->score;
            $score->fill(['score' => $current_score])->save();
        }

        $data = [
            'is_new_record' => true,
            'previous_score' => $previous_score,
            'current_score' => $score,
        ];

        return response()->json(['data' => $data], 200);
    }
}
