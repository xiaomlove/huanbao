<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ContentJson
{
    protected function getContentJsonString(Request $request)
    {
        $content = $request->get('content');
        $contentArr = json_decode($content, true);
        if ($contentArr && is_array($contentArr))
        {
            return $content;
        }
        else
        {
            return json_encode([
                [
                    'type' => 'text',
                    'data' => ['text' => $content],
                ],
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}