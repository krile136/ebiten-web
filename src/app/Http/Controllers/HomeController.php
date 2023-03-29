<?php

namespace App\Http\Controllers;

use App\Models\OneTimeToken;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): View
    {
        $user = Auth::user();
        $plain_text_token = Str::random(40);
        $one_time_token = OneTimeToken::query()->create(
            [
                'user_id' => $user->id,
                'token' => hash('sha256', $plain_text_token),
                'is_used' => false,
            ]
        );

        $ciper_text = 'pNAd9y5cVWEH7LP+IVE+DuvLMWGfI4sgpV5tJ4RJGeM=';
        $iv = '0de7e2e7fc41a0cccb16ef5e91186339';
        $key = '645E739A7F9F162725C1533DC2C5E827';

        // 暗号化
        $text = 'exampleplaintext......';
        $c = openssl_encrypt($text, 'AES-128-CBC', hex2bin($key), 0, hex2bin($iv));
        logger()->debug('encrypt '.$c);

        // 復号化
        $original = openssl_decrypt($ciper_text, 'AES-128-CBC', hex2bin($key), 0, hex2bin($iv));
        logger()->debug('original: '.$original);

        return view('home', compact('user', 'plain_text_token'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function minesweeper(): View
    {
        $user = Auth::user();
        $plain_text_token = Str::random(40);
        $one_time_token = OneTimeToken::query()->create(
            [
                'user_id' => $user->id,
                'token' => hash('sha256', $plain_text_token),
                'is_used' => false,
            ]
        );

        $ciper_text = 'pNAd9y5cVWEH7LP+IVE+DuvLMWGfI4sgpV5tJ4RJGeM=';
        $iv = '0de7e2e7fc41a0cccb16ef5e91186339';
        $key = '645E739A7F9F162725C1533DC2C5E827';

        return view('minesweeper', compact('user', 'plain_text_token'));
    }

    /**
     * Return input view
     *
     * @return View|Factory
     */
    public function input(): View
    {
        return view('input');
    }
}
