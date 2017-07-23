<?php

namespace App\Http\Requests;

trait FormatErrorsTrait
{
    /**
     * 重写  FormRequest 中的  response() 方法，返回所需格式的错误信息
     * 
     * @param array $errors
     */
    public function response(array $errors)
    {
        if ($this->expectsJson())
        {
            $firstError = current($errors);
            return response()->json(normalize($firstError[0], ['errors' => $errors]));
        }
        return parent::response($errors);
    }
}