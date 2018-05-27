<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommentDetail;
use App\User;
use App\Models\Forum;
use App\Models\Topic;
use GuzzleHttp\Client;
use App\Models\HuisuoJishi;

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
        $request->request->set('aaa', 1111);
        dd(__CLASS__);
        return app()->call(__CLASS__ . "@test2");
    }

    public function test2()
    {
        dd(request()->all());
    }
}
