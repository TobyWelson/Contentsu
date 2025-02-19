<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Http\Rules\OkUrl;

class StorePost extends FormRequest
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
            'title' => 'required|max:160',
            'category' => 'required',
            'url' => ['required','url','active_url', new OkUrl]
        ];
    }
}