<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'email' => 'required|email|unique:users',
            'password' => ['required', 'regex:/^[\w]+$/', 'min:6', 'max:20'],
        ]);
        return $v;
    }
}
