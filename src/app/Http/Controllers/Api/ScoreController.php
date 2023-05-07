<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\UseCases\Api\Score\GetIndexAction;
use App\UseCases\Api\Score\StoreOrUpdateAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class ScoreController extends Controller
{
    /**
     * スコアのランキングを取得する
     *
     * @return JsonResponse
     */
    public function index(GetIndexAction $action, int $user_id): JsonResponse
    {
        /**
         * @var string $encrypted
         * @var MessageBag $error_bag
         * */
        [$encrypted, $error_bag] = $action($user_id);
        if ($error_bag->isNotEmpty()) {
            $error_message = $error_bag->get('error');

            return response()->json(['error' => $error_message], 500);
        }

        return response()->json(['data' => $encrypted], 200);
    }

    /**
     * ハイスコアを作成/更新をする
     *
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function storeOrUpdate(StoreOrUpdateAction $action, Request $request): JsonResponse
    {
        $body = $request->all();

        /**
         * @var string $encrypted
         * @var MessageBag $error_bag
         * */
        [$encrypted, $error_bag] = $action($body);
        if ($error_bag->isNotEmpty()) {
            $error_message = $error_bag->get('error');

            return response()->json(['error' => $error_message], 500);
        }

        return response()->json(['data' => $encrypted], 200);
    }
}
