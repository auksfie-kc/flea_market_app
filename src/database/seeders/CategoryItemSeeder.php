<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;

class CategoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // すべてのカテゴリーIDを配列で取得
        $categoryIds = Category::pluck('id')->toArray();

        $items = Item::all();

        foreach ($items as $item) {

            // ランダムに2つのカテゴリーを選択して関連付ける
            $randomCategoryIds = collect($categoryIds)
                ->shuffle()
                ->take(2)
                ->toArray();
            // アイテムにカテゴリーを貼る
            $item->categories()->sync($randomCategoryIds);
        }
    }
}