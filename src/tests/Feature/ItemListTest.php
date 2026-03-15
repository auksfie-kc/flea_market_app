<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_in_user_cannot_see_own_items_in_all_tab()
    {
        $this->seed(\Database\Seeders\ConditionSeeder::class);

        // ユーザー作成
        $user = User::factory()->create();

        // 他ユーザー作成
        $otherUser = User::factory()->create();

        Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => 1,
            'name' => '自分の商品',
        ]);

        Item::factory()->create([
            'user_id' => $otherUser->id,
            'condition_id' => 1,
            'name' => '他人の商品',
        ]);

        // ログインして一覧ページへ
        $response = $this->actingAs($user)->get('/?tab=all');

        // ページ表示確認
        $response->assertStatus(200);

        // 自分の商品は表示されない
        $response->assertDontSee('自分の商品');

        // 他人の商品は表示される
        $response->assertSee('他人の商品');
    }}
