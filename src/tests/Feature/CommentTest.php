<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    // ログインユーザーはコメントできる
    public function test_logged_in_user_can_comment()
    {
        $user = User::factory()->create();

        $condition = Condition::create([
            'condition' => '良好',
        ]);

        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'condition_id' => $condition->id,
        ]);

        $response = $this->actingAs($user)->post("/item/{$item->id}/comments", [
            'comment' => '購入を検討しています',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => '購入を検討しています',
        ]);
    }

    // ゲストユーザーはコメントできない
    public function test_guest_user_cannot_comment()
    {
        $condition = Condition::create([
            'condition' => '良好',
        ]);

        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'condition_id' => $condition->id,
        ]);

        $response = $this->post("/item/{$item->id}/comments", [
            'comment' => '購入を検討しています',
        ]);
        // 未ログインユーザーはログイン画面にリダイレクトされることを確認
        $response->assertStatus(302);
        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'comment' => '購入を検討しています',
        ]);
    }

    // コメントのバリデーション
    public function test_comment_is_required()
    {
        $user = User::factory()->create();

        $condition = Condition::create([
            'condition' => '良好',
        ]);

        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'condition_id' => $condition->id,
        ]);

        $response = $this->actingAs($user)->post("/item/{$item->id}/comments", [
            'comment' => '',
        ]);

        $response->assertSessionHasErrors(['comment']);
    }
    // コメントは255文字以内であること
    public function test_comment_must_be_255_characters_or_less()
    {
        $user = User::factory()->create();

        $condition = Condition::create([
            'condition' => '良好',
        ]);

        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'condition_id' => $condition->id,
        ]);

        $response = $this->actingAs($user)->post("/item/{$item->id}/comments", [
            // 256文字のコメントを送信した場合、バリデーションエラーになることを確認
            'comment' => str_repeat('あ', 256),
        ]);

        $response->assertSessionHasErrors(['comment']);
    }
}
