<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommentDetail;
use App\User;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function test(Request $request)
    {
        $user = User::find(1);
        $token = $user->createToken('my test token')->accessToken;
        dd($token);
    }
}
