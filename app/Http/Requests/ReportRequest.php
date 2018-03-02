<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\HuisuoJishi;
use App\Models\Topic;
use Illuminate\Validation\Rule;

class ReportRequest extends FormRequest
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
            'jishi_id' => ['required', Rule::exists($huisuoJishiTable, 'id')->where('type', HuisuoJishi::TYPE_JISHI)],
            'huisuo_id' => ['required', Rule::exists($huisuoJishiTable, 'id')->where('type', HuisuoJishi::TYPE_HUISUO)],
            'jishi_top_value' => "required|numeric|min:0|max:10",
            'jishi_middle_value' => "required|numeric|min:0|max:10",
            'jishi_bottom_value' => "required|numeric|min:0|max:10",
            'jishi_figure_value' => "required|numeric|min:0|max:10",
            'jishi_appearance_value' => "required|numeric|min:0|max:10",
            'jishi_attitude_value' => "required|numeric|min:0|max:10",
            'jishi_technique_value' => "required|numeric|min:0|max:10",
            'huisuo_environment_facility_value' => "required|numeric|min:0|max:10",
            'huisuo_service_attitude_value' => "required|numeric|min:0|max:10",
        ];
    }

    public function attributes()
    {
        return [
            'jishi_id' => 'JS',
            'huisuo_id' => 'HS',
            'jishi_top_value' => '上路指数',
            'jishi_middle_value' => '中路指数',
            'jishi_bottom_value' => '下路指数',
            'jishi_figure_value' => '身材得分',
            'jishi_appearance_value' => '颜值得分',
            'jishi_attitude_value' => '态度得分',
            'jishi_technique_value' => '技术得分',
            'huisuo_environment_facility_value' => '环境设施得分',
            'huisuo_service_attitude_value' => '服务态度得分',
        ];
    }
}
