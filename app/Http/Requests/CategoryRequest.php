<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CategoryRequest extends BaseRequest
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
        $rules = [];

        if ($this->getMethod() == "POST") {
            $rules += [
                "name" => [
                    "required", 
                    "unique:categories,name"
                    // Rule::unique("categories", "name")->ignore($this->category)
                ],
            ];
        }

        if ($this->getMethod() == "PATCH") {
            $rules += [
                "name" => [
                    "required", 
                    "unique:categories,name,{$this->route("id")}"
                ],
            ];
        }
        return $rules;
    }
}
