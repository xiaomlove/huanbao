<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommentRequest extends FormRequest
{
    use FormatErrorsTrait;
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
    
    public function validator()
    {
        $data = \Input::all();
        $v = \Validator::make($data, [
            'tid' => 'required|exists:topics,id',
            'content' => 'required|min:2|max:200',
        ]);
        $v->sometimes('pid', 'numeric|exists:comments,id', function($input) {
            return $input->pid > 0;
        });
    
        return $v;
    }
}
