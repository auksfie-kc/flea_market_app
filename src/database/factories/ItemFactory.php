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
            'item_images/bag.jpg',
            'item_images/hdd.jpg',
            'item_images/onion.jpg',
            'item_images/shoes.jpg',
            'item_images/laptop.jpg',
            'item_images/mic.jpg',
            'item_images/watch.jpg',
            'item_images/tumbler.jpg',
            'item_images/mill.jpg',
            'item_images/makeup.jpg',
        ];

        return [

            'user_id' => null,
            'condition_id' => $this->faker->numberBetween(1, 4), // 1から4までのランダムなcondition
            'name' => $this->faker->realText(20), //20文字の自然な日本語
            'img_url' => $this->faker->randomElement($images), // 画像パスをランダムに選択
            'brand' => $this->faker->optional()->company(), //ブランド名（会社名）をランダムに生成、50%の確率でnull
            'description' => $this->faker->realText(100), //100文字の自然な日本語
            'price' => $this->faker->numberBetween(500, 50000), // 500円から50,000円のランダムな価格
        ];
    }
}
