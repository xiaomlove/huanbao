<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        return [
            //
        ];
    }
    
    public function validator()
    {
        $uid = \Route::current()->parameter('user');
        $uid = intval($uid);
        $v = \Validator::make(\Input::all(), [
            'name' => [
                Rule::unique('users')->ignore($uid),
            ],
            'email' => [
                'required', 
                'email', 
                Rule::unique('users')->ignore($uid),
            ],
            'avatar' => ['url', "attachment"],
        ]);
        $v->sometimes(
            'password', 
            ['confirmed', 'regex:/^[\w]+$/', 'min:6', 'max:20'], 
            function($input) {return $input->password != '';}
        );
        return $v;
    }
}
