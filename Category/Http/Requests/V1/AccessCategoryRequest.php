<?php

namespace Modules\Category\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class AccessCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($id = null)
    {
        $arr = [
            'name' => 'required|min:3|unique:categories',
            'desc' => 'min:3'
        ];
        if ($id) {
            $arr = [
                'name' => 'min:3|unique:categories',
                'desc' => 'min:3'
            ];
        }
        return $arr;
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
}
