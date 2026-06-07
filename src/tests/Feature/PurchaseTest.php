<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    //購入のテスト
    public function test_user_can_purchase()
    {
        // テスト用の購入ユーザー作成（プロフィールも同時に作成済）
        $user = User::factory()->create();

        // 出品者
        $seller = User::factory()->create();

        $condition = Condition::create([
            'condition' => '良好',
        ]);
        $item = Item::factory()->create([
            'condition_id' => $condition->id,
            'user_id' => $seller->id,
        ]);
        // ログインして購入ページへ
        $response = $this->actingAs($user)->post("/purchase/{$item->id}", [
            'item_id' => $item->id,
            'payment_method' => 'convenience_store', // コンビニ決済を選択
        ]);
        $response->assertStatus(302);

        //購入が完了し、sold_itemsテーブルに保存されている
        $this->assertDatabaseHas('sold_items', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'payment_method' => 'convenience_store',
            'status' => 'pending',
        ]);

        //商品一覧画面にてSoldが表示される
        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Sold');

        //プロフィールの購入済み一覧に表示される
        $response = $this->actingAs($user)->get('/mypage?tab=buy');
        $response->assertStatus(200);
        $response->assertSee($item->name);

    }
}