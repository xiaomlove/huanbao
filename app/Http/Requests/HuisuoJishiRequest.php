<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\HuisuoJishi;
use App\Models\Attachment;
use App\Rules\TuWenContent;
use App\Models\Contact;

class HuisuoJishiRequest extends FormRequest
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
        $contactTypes = Contact::listTypes();
        $v = \Validator::make($params, [
            'name' => 'required|min:1|max:40',
            'cover' => 'required|exists:attachments,id',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'address' => 'required',
            'age' => 'required|numeric|min:16',
            'price' => 'required|numeric',
            'contacts' => 'array',
            'description' => ['required',  new TuWenContent()],
        ]);
        $v->setAttributeNames([
            'cover' => '封面',
            'price' => '价格',
        ]);
        $v->after(function($validator) use ($params, $contactTypes) {
            $contacts = $params['contacts'];
            foreach ($contacts['type'] as $k => $type)
            {
                $index = $k + 1;
                if (isset($contactTypes[$type]))
                {
                    if (empty($contacts['account'][$k]))
                    {
                        $validator->errors()->add("contacts.account.$k", "联系方式{$index} 缺少 {$contactTypes[$type]}");
                    }
                    if (!empty($contacts['image'][$k]))
                    {
                        $id = $contacts['image'][$k];
                        $info = Attachment::find($id);
                        if (empty($info))
                        {
                            $validator->errors()->add("contacts.image.$k", "联系方式{$index} 不存在 {$id} 的图片");
                        }
                    }
                }
            }
        });
        return $v;
    }
    
}
