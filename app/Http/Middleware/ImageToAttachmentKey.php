<?php

namespace App\Http\Middleware;

use Closure;

class ImageToAttachmentKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $method = $request->method();
        if (in_array($method, ['POST', 'PUT', 'PATCH']))
        {
            foreach ($request->all() as $key => $value)
            {
                if (strpos($key, '_image') !== false)
                {
                    $request->request->set($key, attachmentKey($value));
                }
            }
        }

        return $next($request);
    }
}
