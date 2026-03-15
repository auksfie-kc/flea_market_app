<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Like;
use App\Models\User;
use App\Models\Item;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //UserとItemのID一覧を取得
        $userIds = User::pluck('id');
        $itemIds = Item::pluck('id');

        //どちらかが0件なら処理しない
        if ($userIds->isEmpty() || $itemIds->isEmpty()) {
            return;
        }

        $likesPerUser = 5; //1人あたりのいいね数5

        foreach ($userIds as $userId) {

            //20商品の中からランダムで5つ選ぶ
            $pickedItemIds = $itemIds->random(
                min($likesPerUser, $itemIds->count())
            );

            //選ばれた商品にいいねを作る
            foreach ($pickedItemIds as $itemId) {
                Like::firstOrCreate([
                    'user_id' => $userId,
                    'item_id' => $itemId,
                ]);
            }
    }
}
}