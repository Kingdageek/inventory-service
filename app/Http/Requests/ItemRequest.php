<?php

namespace App\Http\Requests;

class ItemRequest extends BaseRequest
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
            "article_id" => ["required", "exists:articles,id"],
            "shelf_life" => ["date", "nullable"]
        ];

        // if ($this->getMethod() == "PATCH") {
        //     $rules += [
        //         "id" => "required|email|unique:users,email," . $this->route("id")
        //     ];
        // }
        return $rules;
    }
}