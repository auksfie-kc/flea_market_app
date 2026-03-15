<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'user_id' => 1,
                'name' => '腕時計',
                'brand' => 'Rolax',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'img_url' => 'storage/item_images/watch.jpg',
                'condition_id' => 1,
            ],
            [
                'user_id' => 1,
                'name' => 'HDD',
                'brand' => '西芝',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'img_url' => 'storage/item_images/hdd.jpg',
                'condition_id' => 2,
            ],
            [
                'user_id' => 1,
                'name' => '玉ねぎ3束',
                'brand' => 'なし',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束のセット',
                'img_url' => 'storage/item_images/onion.jpg',
                'condition_id' => 3,
            ],
            [
                'user_id' => 1,
                'name' => '革靴',
                'brand' => null,
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'img_url' => 'storage/item_images/shoes.jpg',
                'condition_id' => 4,
            ],
            [
                'user_id' => 1,
                'name' => 'ノートPC',
                'brand' => null,
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'img_url' => 'storage/item_images/laptop.jpg',
                'condition_id' => 1,
            ],
            [
                'user_id' => 1,
                'name' => 'マイク',
                'brand' => 'なし',
                'price' => 8000,
                'description' => '高音質なレコーディング用マイク',
                'img_url' => 'storage/item_images/mic.jpg',
                'condition_id' => 2,
            ],
            [
                'user_id' => 1,
                'name' => 'ショルダーバッグ',
                'brand' => null,
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'img_url' => 'storage/item_images/bag.jpg',
                'condition_id' => 3,
            ],
            [
                'user_id' => 1,
                'name' => 'タンブラー',
                'brand' => 'なし',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'img_url' => 'storage/item_images/tumbler.jpg',
                'condition_id' => 4,
            ],
            [
                'user_id' => 1,
                'name' => 'コーヒーミル',
                'brand' => 'Starbacks',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'img_url' => 'storage/item_images/mill.jpg',
                'condition_id' => 1,
            ],
            [
                'user_id' => 1,
                'name' => 'メイクセット',
                'brand' => null,
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'img_url' => 'storage/item_images/makeup.jpg',
                'condition_id' => 2,
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
