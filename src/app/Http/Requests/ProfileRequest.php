<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'name' => 'required|string|max:20',
            'postcode' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required|string',
            'img_url' => 'nullable|image|mimes:jpeg,png',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'ユーザー名を入力してください',
            'name.string' => 'ユーザー名は文字列で入力してください',
            'name.max' => 'ユーザー名は20文字以内で入力してください',
            'postcode.required' => '郵便番号を入力してください',
            'postcode.regex' => '郵便番号は「123-4567」で入力してください',
            'address.required' => '住所を入力してください',
            'address.string' => '住所は文字列で入力してください',
            'img_url.image' => '画像ファイルをアップロードしてください',
            'img_url.mimes' => '画像はjpegまたはpng形式のファイルを指定してください',
        ];
    }
}
