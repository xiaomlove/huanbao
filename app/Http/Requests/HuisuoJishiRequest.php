<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\HuisuoJishi;
use App\Models\Topic;
use App\Models\Attachment;
use App\Rules\TuWenContent;
use App\Models\Contact;

class HuisuoJishiRequest extends FormRequest
{
    protected $model;

    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        $this->model = new HuisuoJishi(['type' => HuisuoJishi::getGuessType()['type']]);
    }

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
        $typeStr = $this->model->listTypes(true);
        $topicTable = with(new Topic())->getTable();
        $huisuoJishiTable = (new HuisuoJishi())->getTable();
        $id = \Route::current()->parameter('huisuojishi', 0);
        return [
            'name' => 'required|min:2|max:10',
            'tid' => ["exists:$topicTable,id", Rule::unique($huisuoJishiTable)->ignore($id)],
            'short_name' => ['required', 'regex:/\w+/i', 'min:2', 'max:10'],
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'background_image' => 'required|url',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'short_name' => $this->model->short_name_label,
            'background_image' => '背景图片',
            'tid' => '帖子ID',
            'province' => '省',
            'district' => '区',

         ];
    }

    public function messages()
    {
        return [
            'tid.unique' => '帖子ID已经被关联',
        ];
    }

}
