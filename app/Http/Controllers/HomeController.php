<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommentDetail;
use App\User;
use App\Models\Forum;
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
        $commentDetail = CommentDetail::findOrFail(61);
        $attachment = $commentDetail->attachments()->create([
            'uid' => \Auth::id(),
            'key' => (string)\Uuid::uuid4(),
            'mime_type' => "image/jpeg",
            'size' => 999,
            'width' => 889998,
            'height' => 99,
        ]);
//        $commentDetail->attachments()->updateExistingPivot($attachment->id, ['attachment_key' => 'sbsb']);
    }
}
