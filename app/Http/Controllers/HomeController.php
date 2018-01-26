<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommentDetail;
use App\User;
use App\Models\Forum;
use GuzzleHttp\Client;

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
        $client = new Client();
        $response = $client->get(url('/admin'));

        echo (string)$response->getBody();
    }
}
