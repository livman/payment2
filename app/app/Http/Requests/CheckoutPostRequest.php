<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutPostRequest extends FormRequest
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
        $regex = "/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/";

        return [
            'firstname' => 'required|max:8',
            'lastname' => 'required',
            'amount' => array('required','regex:'. $regex)
        ];
    }

    public function messages()
    {
        return [
            'firstname.required'    => 'A firstname is required',
            'lastname.required'  => 'A lastname is required',
            'amount.required'  => 'A amount is required',
        //    'amount.regex'  => 'A amount is wrong format',
        ];
    }
}
