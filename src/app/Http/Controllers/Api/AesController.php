<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\Aes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AesController extends Controller
{
    use Aes;

    /**
     * @return JsonResponse
     */
    public function getEncrypt(Request $request): JsonResponse
    {
        $data = $request->all();
        $encrypted = $this->encrypt($data);

        return response()->json(['data' => $encrypted], 200);
    }

    /**
     * @return JsonResponse
     */
    public function getDecrypt(Request $request): JsonResponse
    {
        $data = $request->all();
        if (isset($data['data']) === false) {
            logger()->warning('ボディにdataを持たないリクエストが送られました。不正なAPIリクエストの可能性があります！');

            return response()->json(['message' => 'Error is occured'], 500);
        }
        $decrypted = $this->decrypt($data['data']);

        return response()->json(['data' => $decrypted], 200);
    }
}
