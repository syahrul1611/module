<?php

namespace Modules\Post\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class AccessPostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($id = null)
    {
        $arr = [
            'title' => 'required|max:100',
            'image' => 'required|file|mimes:png,jpg,jepg',
            'content' => 'required|min:3',
            'category' => 'required'
        ];
        if ($id) {
            $arr = [
                'title' => 'max:100',
                'image' => 'file|mimes:png,jpg,jepg',
                'content' => 'min:3',
                'category' => ''
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
