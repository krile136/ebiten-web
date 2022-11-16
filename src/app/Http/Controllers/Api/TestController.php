<?php

namespace App\Http\Controller\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestController extends Controller
{
    public function testPost(Request $request): JsonResource
    {
        $credentials = $request->validate(
            [
                'data' => ['required'],
            ]
        );

        $data = $credentials['data'];
        $exploded_data = explode('|', $data);
        $iv = $exploded_data[0];
        $encrypted = $exploded_data[1];
    }
}
