<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\HuisuoJishi;
use Carbon\Carbon;

class HuisuoJishiRelationshipRequest extends FormRequest
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
        $huisuoJishiTable = (new HuisuoJishi())->getTable();
        return [
            'huisuo_id' => ["required", Rule::exists($huisuoJishiTable, 'id')->where("type", HuisuoJishi::TYPE_HUISUO)],
            'jishi_id' => ["required", Rule::exists($huisuoJishiTable, 'id')->where("type", HuisuoJishi::TYPE_JISHI)],
            'begin_time' => ['required', 'date', 'before_or_equal:' . Carbon::now()->toDateTimeString()],
        ];
    }

    public function attributes()
    {
        return [
            'begin_time' => '开始时间',
        ];
    }
}
