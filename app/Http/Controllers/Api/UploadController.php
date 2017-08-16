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
        \Log::info(sprintf("%s, uploaded: %s", __METHOD__, var_export($request->files, true)));
        \Log::info(sprintf("%s, uploaded: %s", __METHOD__, var_export($request->file('image'), true)));
        \Log::info(sprintf("%s, uploaded: %s", __METHOD__, var_export($_FILES, true)));
        $user = $this->apiUser();
        $result = $this->attachment->create($request->file('image'), $user->id);
        if ($result['ret'] != 0)
        {
            return $result;
        }
        $data = fractal()
        ->collection($result['data'])
        ->transformWith(new AttachmentTransformer())
        ->toArray();
        return normalize(0, "OK", $data);
    }
}
