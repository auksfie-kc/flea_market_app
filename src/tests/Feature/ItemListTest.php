<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Condition;
use App\Models\SoldItem;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    //商品一覧取得
    public function test_logged_in_user_cannot_see_own_items_in_all_tab()
    {
        $this->seed(\Database\Seeders\ConditionSeeder::class);

        // ユーザー作成
        $user = User::factory()->create();

        // 他ユーザー作成
        $otherUser = User::factory()->create();

        $condition = Condition::create([
            'condition' => '良好',
        ]);

        // アイテム作成
        Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => '自分の商品',
        ]);

        Item::factory()->create([
            'user_id' => $otherUser->id,
            'condition_id' => $condition->id,
            'name' => '他人の商品',
        ]);

        // 売却済み商品
        $soldItem = Item::factory()->create([
            'user_id' => $otherUser->id,
            'condition_id' => $condition->id,
            'name' => '売却済み商品',
        ]);

        // sold_itemsに登録（購入済み状態にする）
        SoldItem::create([
            'item_id' => $soldItem->id,
            'user_id' => $user->id,
            'sold_postcode' => '123-4567',
            'sold_address' => 'テスト住所',
            'payment_method' => 'convenience_store',
            'status' => 'pending',
        ]);

        // ログインして一覧ページへ
        $response = $this->actingAs($user)->get('/?tab=all');

        // ページ表示確認
        $response->assertStatus(200);

        // 自分の商品は表示されない
        $response->assertDontSee('自分の商品');

        // 他人の商品は表示される
        $response->assertSee('他人の商品');

        // 売却済み商品も表示される
        $response->assertSee('売却済み商品');
        // 売却済み商品にはSold表示がされる
        $response->assertSee('Sold');
    }

    //マイリスト一覧取得(いいねした商品のみ表示)
    public function test_mylist_displays_only_liked_items()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $condition = Condition::create([
            'condition' => '良好',
        ]);

        $likedItem = Item::factory()->create([
            'user_id' => $otherUser->id,
            'condition_id' => $condition->id,
            'name' => 'いいねした商品',
        ]);

        $notLikedItem = Item::factory()->create([
            'user_id' => $otherUser->id,
            'condition_id' => $condition->id,
            'name' => 'いいねしてない商品',
        ]);

        // likesテーブルに登録する
        $user->likedItems()->attach($likedItem->id);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいねした商品');
        $response->assertDontSee('いいねしてない商品');
    }

    // マイリスト取得：購入済み商品はSoldと表示される
    public function test_sold_item_in_mylist_displays_sold_label()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $condition = Condition::create([
            'condition' => '良好',
        ]);

        $likedItem = Item::factory()->create([
            'user_id' => $otherUser->id,
            'condition_id' => $condition->id,
            'name' => '購入済みのいいね商品',
        ]);

        // likesテーブルに登録する
        $user->likedItems()->attach($likedItem->id);

        // sold_itemsテーブルに登録する
        SoldItem::create([
            'item_id' => $likedItem->id,
            'user_id' => $user->id,
            'sold_postcode' => '123-4567',
            'sold_address' => 'テスト住所',
            'payment_method' => 'convenience_store',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('購入済みのいいね商品');
        $response->assertSee('Sold');
    }

    // マイリスト取得：未認証の場合は何も表示されない
    public function test_guest_user_cannot_see_any_items_in_mylist()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $condition = Condition::create([
            'condition' => '良好',
        ]);

        $likedItem = Item::factory()->create([
            'user_id' => $otherUser->id,
            'condition_id' => $condition->id,
            'name' => 'ログイン時だけ見える商品',
        ]);

        $user->likedItems()->attach($likedItem->id);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertDontSee('ログイン時だけ見える商品');
    }

    //商品検索
    public function test_search_item()
    {
        $condition = Condition::create([
            'condition' => '良好',
        ]);

        Item::factory()->create([
            'name' => 'ノートPC',
            'price' => 45000,
            'brand' => '',
            'description' => '高性能なノートパソコン',
            'img_url' => 'storage/item_images/laptop.jpg',
            'user_id' => User::factory()->create()->id,
            'condition_id' => $condition->id,
        ]);

        $response = $this->get('/?keyword=ノートPC');
        $response->assertStatus(200);
        $response->assertSee('ノートPC');
        $response->assertViewHas('items');

    }
    // 検索キーワードがマイリスト画面でも保持される
    public function test_search_keyword_is_kept_in_mylist()
    {
        $user = User::factory()->create();
        //ログインして確認
        $response = $this->actingAs($user)->get('/?tab=mylist&keyword=ノートPC');

        $response->assertStatus(200);
        $response->assertSee('ノートPC');
    }
}
