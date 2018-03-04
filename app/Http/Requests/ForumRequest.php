<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ForumRequest extends FormRequest
{
    
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
        
    }
    
    public function validator()
    {
        $v = \Validator::make(\Input::all(), [
            'name' => 'required|max:20',
            'description' => 'nullable|max:200',
        ]);

        return $v;
    }

}
