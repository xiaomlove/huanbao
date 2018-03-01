<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';

    protected $fillable = [
        'key', 'uid', 'tid', 'jishi_id', 'jishi_name', 'huisuo_id', 'huisuo_name',
        'jishi_top_value', 'jishi_top_description',
        'jishi_middle_value', 'jishi_middle_description',
        'jishi_bottom_value', 'jishi_bottom_description',
        'jishi_figure_value', 'jishi_figure_description',
        'jishi_appearance_value', 'jishi_appearance_description',
        'jishi_attitude_value', 'jishi_attitude_description',
        'jishi_technique_value', 'jishi_technique_description',
        'huisuo_environment_facility_value', 'huisuo_environment_facility_description',
        'huisuo_service_attitude_value', 'huisuo_service_attitude_description',
    ];
}
