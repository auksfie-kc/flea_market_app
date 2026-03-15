<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */


    public function definition()
    {

        $images = [
            'item_images/item01.jpg',
            'item_images/item02.jpg',
            'item_images/item03.jpg',
            'item_images/item04.jpg',
            'item_images/item05.jpg',
            'item_images/item06.jpg',
            'item_images/item07.jpg',
            'item_images/item08.jpg',
            'item_images/item09.jpg',
            'item_images/item10.jpg',
        ];

        return [

            'user_id' => null,
            'condition_id' => $this->faker->numberBetween(1, 4), // 1から4までのランダムなcondition
            'name' => $this->faker->realText(20), //20文字の自然な日本語
            'img_url' => 'storage/'.$this->faker->randomElement($images), // 画像パスをランダムに選択
            'brand' => $this->faker->optional()->company(), //ブランド名（会社名）をランダムに生成、50%の確率でnull
            'description' => $this->faker->realText(100), //100文字の自然な日本語
            'price' => $this->faker->numberBetween(500, 50000), // 500円から50,000円のランダムな価格
        ];
    }
}
