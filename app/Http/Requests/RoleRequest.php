<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
        $params = \Input::all();
        $v = \Validator::make($params, [
            'name' => [
                'required', 'min:1', 'max:20', 'regex:|^[\w]+$|',
                Rule::unique('roles')->ignore(\Route::current()->parameter('role')),
            ],
            'display_name' => 'required|min:1|max:10',
        ]);
        return $v;
    }
}
