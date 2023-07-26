<?php

namespace App\Http\Requests;

class ArticleRequest extends BaseRequest
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
        $rules = [
            "category_id" => ["required", "integer", "exists:categories,id"], 
            "packaging" => ["string", "nullable"], 
            "has_shelf_life" => ["required", "boolean"],
            "min_amount" => ["integer", "nullable"], 
            "amount" => ["integer", "nullable"], 
            "checker" => ["required", "string"]
        ];

        if ($this->getMethod() == "POST") {
            $rules += [
                "name" => ["required", "unique:articles,name"]
            ];
        }

        if ($this->getMethod() == "PATCH") {
            $rules += [
                "name" => ["required", "unique:articles,name,{$this->route("id")}"]
           ];
        }
        return $rules;
    }
}