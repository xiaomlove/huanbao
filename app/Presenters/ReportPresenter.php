<?php

namespace App\Presenters;

use App\Models\Report;

class ReportPresenter
{
    public function getScoreDesc(Report $report)
    {
        $result = sprintf('<span alt="指数">%s</span><span alt="态度">/%s</span><span alt="技术">/%s</span><span alt="身材">/%s</span><span alt="颜值">/%s</span><span alt="环境设施">/%s</span><span alt="服务态度">/%s</span>',
            $report->jishi_top_value, $report->jishi_middle_value, $report->jishi_bottom_value,
            $report->jishi_attitude_value, $report->jishi_technique_value, $report->jishi_figure_value, $report->jishi_appearance_value,
            $report->huisuo_environment_facility_value, $report->huisuo_service_attitude_value
        );
        return $result;
    }
}