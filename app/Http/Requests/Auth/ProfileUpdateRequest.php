<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'phone' => 'required|digits_between:5,20|unique:users,phone,' . $this->user()->id, 
            'gender' => 'sometimes|max:50',
            'email' => 'required|unique:users,email,'.$this->user()->id,
            'birth' => 'sometimes|date_format:d-m-Y',
            'image' => ['image', 'mimes:png,jpg,jpeg,webp','max:2048'],
        ];
    }
}
