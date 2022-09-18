<?php

namespace App\Http\Controllers;

use App\Models\OneTimeToken;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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
    public function index()
    {
        $user = Auth::user();
        $plain_text_token = Str::random(40);
        $one_time_token = OneTimeToken::create([
            'user_id' => $user->id,
            'token' => hash('sha256', $plain_text_token),
            'is_used' => false,
        ]);

        return view('home', compact('user', 'plain_text_token'));
    }
}
