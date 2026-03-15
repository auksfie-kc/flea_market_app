<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\User;
use App\Models\Item;

class CommentSeeder extends Seeder
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
        //1人あたりのコメント数3つ
        $commentsPerUser = 3;

        foreach ($userIds as $userId) {

            //商品の中からランダムで3つ選ぶ
            $pickedItemIds = $itemIds->random(
                min($commentsPerUser, $itemIds->count())
            );
            //選ばれた商品にコメントを作る
            foreach ($pickedItemIds as $itemId) {

                Comment::factory()->create([
                    'user_id' => $userId,
                    'item_id' => $itemId,
                ]);
            }
    }
}
}