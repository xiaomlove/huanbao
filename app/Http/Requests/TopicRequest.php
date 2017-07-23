<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopicRequest extends FormRequest
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
        $v = \Validator::make(\Input::all(), [
            'title' => 'required|min:2|max:40',
            'fid' => 'required|exists:forums,id',
            'content' => 'required|min:2|max:1000',
        ]);
        return $v;
    }
}
