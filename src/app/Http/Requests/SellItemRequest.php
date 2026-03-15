<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellItemRequest extends FormRequest
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
            'name' => 'required|string',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'img_url' => 'required|image|mimes:jpeg,png',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id', //複数選択した値一つ一つがcategoryテーブルに存在するのかを確認
            'condition_id' => 'required|exists:conditions,id',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品の説明を入力してください',
            'description.max' => '商品の説明は255文字以内で入力してください',
            'price.required' => '価格を入力してください',
            'price.numeric' => '価格は数値で入力してください',
            'price.min' => '価格は0円以上で入力してください',
            'img_url.required' => '画像をアップロードしてください',
            'img_url.image' => '画像ファイルをアップロードしてください',
            'img_url.mimes' => '画像はjpegまたはpng形式のファイルを指定してください',
            'category_ids.required' => 'カテゴリーを選択してください',
            'category_ids.array' => 'カテゴリーを正しく選択してください',
            'category_ids.*.exists' => '選択されたカテゴリーは無効です',
            'condition_id.required' => '商品の状態を選択してください',
            'condition_id.exists' => '選択された商品状態は無効です',
        ];
    }
}
