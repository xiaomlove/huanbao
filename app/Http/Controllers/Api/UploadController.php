<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\AttachmentRepository;
use App\Transformers\AttachmentTransformer;

class UploadController extends Controller
{
    protected $attachment;
    
    public function __construct(AttachmentRepository $attachment)
    {
        $this->attachment = $attachment;
    }
    
    public function image(Request $request)
    {
        $user = $this->apiUser();
        $result = $this->attachment->create($request->file('image'), $user->id);
        if ($result['ret'] != 0)
        {
            return $result;
        }
        $transformResult = fractal()
        ->collection($result['data'])
        ->transformWith(new AttachmentTransformer())
        ->toArray();
        return normalize(0, "OK", $transformResult['data']);
    }
}
