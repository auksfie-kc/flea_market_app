<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Condition;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserTest extends TestCase
{
    use RefreshDatabase;

    // ログアウト確認
    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');//ユーザーログイン後、ログアウトのリクエストを送信

        $response->assertStatus(302);
        $this->assertGuest(); // ログアウト後、ユーザーがゲストであることを確認
    }

    // プロフィール表示
    public function test_profile_page_displays_user_info()
    {
        $user = User::factory()->create();

        $condition = Condition::create([
            'condition' => '良好',
        ]);

        // 出品商品
        $item = Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
        ]);

        // 購入商品（簡易的に）
        $user->soldItems()->create([
            'item_id' => $item->id,
            'sold_postcode' => '123-4567',
            'sold_address' => 'テスト住所',
            'payment_method' => 'convenience_store',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    // プロフィール編集初期値の確認
    public function test_profile_edit_shows_existing_data()
    {
        $user = User::factory()->create();

        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'postcode' => '123-4567',
                'address' => 'テスト住所',
                'building' => 'テストビル',
                'img_url' => 'storage/profile_images/sample-user.png',
            ]
        );

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($profile->postcode);
        $response->assertSee($profile->address);
        $response->assertSee($profile->img_url);
    }

    // 商品の出品
    public function test_user_can_sell_item()
    {
        $user = User::factory()->create();

        $condition = Condition::create([
            'condition' => '良好',
        ]);

        $category = Category::create([
            'name' => 'ファッション',
        ]);

        $response = $this->actingAs($user)->post('/sell/store', [
            'condition_id' => $condition->id,
            'category_ids' => [$category->id],
            'name' => 'テスト商品',
            'price' => '1000',
            'brand' => 'テストブランド',
            'img_url' => UploadedFile::fake()->create('item.jpeg',100, 'image/jpeg'),
            'description' => 'テスト説明',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
            'price' => '1000',
        ]);
    }
}

