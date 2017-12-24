<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ForumTaxonomy;

class ForumTaxonomyRequest extends FormRequest
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
        $table = app(ForumTaxonomy::class)->getTable();
        $id = \Route::current()->parameter('forumtaxonomy');
        return [
            'name' => "required|unique:{$table},name,{$id}",
        ];
    }
}
