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
        $topic = Topic::with('mainFloor', 'mainFloor.detail', 'mainFloor.detail.attachments')->findOrFail(4);
        $attachmentKeyHad = $topic->mainFloor->detail->attachments->pluck(null, 'key');
        dd($attachmentKeyHad->pluck('pivot.attachment_id'));
    }
}
