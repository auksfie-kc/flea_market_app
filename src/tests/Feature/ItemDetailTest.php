<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Comment;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    //商品詳細情報の取得
    public function test_item_detail_page_displays_item_information()
    {
        $user = User::factory()->create([
            'name' => 'コメントユーザー',
        ]);

        $condition = Condition::create([
            'condition' => '良好',
        ]);

        $category1 = Category::create([
            'name' => '家電',
        ]);
        $category2 = Category::create([
            'name' => 'メンズ',
        ]);

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => 'ノートPC',
            'brand' => 'テストブランド',
            'price' => 45000,
            'description' => '高性能なノートパソコン',
            'img_url' => 'storage/item_images/laptop.jpg',
        ]);

        $item->categories()->attach([
            $category1->id,
            $category2->id,
        ]);

        // いいねを1件作る
        $item->likes()->create([
            'user_id' => $user->id,
        ]);

        // コメントを1件作る
        Comment::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => '購入を検討しています',
        ]);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);

        $response->assertSee('storage/item_images/laptop.jpg');
        $response->assertSee('ノートPC');
        $response->assertSee('テストブランド');
        $response->assertSee('45,000');
        $response->assertSee('高性能なノートパソコン');
        $response->assertSee('家電');
        $response->assertSee('メンズ');
        $response->assertSee('良好');
        $response->assertSee('コメントユーザー');
        $response->assertSee('購入を検討しています');

        // いいね数・コメント数
        $response->assertSee('1');
    }

    // いいね機能登録のテスト
    public function test_user_can_like_item()
    {
        $user = User::factory()->create();

        $condition = Condition::create([
            'condition' => '良好',
        ]);

        $item = Item::factory()->create([
            'condition_id' => $condition->id,
            'user_id' => User::factory()->create()->id,
        ]);

        $response = $this->actingAs($user)->post("/item/{$item->id}/like");

        //likesテーブルへの登録確認
        $response->assertStatus(302);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }


    //いいね登録時表示アイコンのテスト
    public function test_liked_item_displays_red_heart()
    {
        $user = User::factory()->create();

        $condition = Condition::create([
            'condition' => '良好',
        ]);

        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'condition_id' => $condition->id,
        ]);

        // likesテーブルに登録
        $item->likes()->create([
            'user_id' => $user->id,
        ]);

        // 詳細画面へ
        $response = $this->actingAs($user)
            ->get("/item/{$item->id}");

        $response->assertStatus(200);

        // 赤ハート表示
        $response->assertSee('❤️');

        // 白ハートは表示されない
        $response->assertDontSee('🤍');
    }

    // いいね解除のテスト
    public function test_user_can_unlike_item()
    {
        $user = User::factory()->create();

        $condition = Condition::create([
            'condition' => '良好',
        ]);

        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'condition_id' => $condition->id,
        ]);

        // 先にいいね登録
        $item->likes()->create([
            'user_id' => $user->id,
        ]);

        // いいね解除
        $response = $this->actingAs($user)
            ->delete("/item/{$item->id}/like");

        $response->assertStatus(302);

        // likesテーブルから削除されているか確認
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}