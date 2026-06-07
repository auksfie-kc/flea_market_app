<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;


class LoginTest extends TestCase
{

    use RefreshDatabase;

    //正常なログインのテスト
    public function test_user_can_login()
    {
        // ユーザー作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticated();//loginできているか確認
    }

    // メールアドレスのバリデーション
    public function test_email_is_required()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);
        $errors = session('errors');
        $this->assertEquals('メールアドレスを入力してください', $errors->first('email'));
    }

    // パスワードのバリデーション
    public function test_password_is_required()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
        $errors = session('errors');
        $this->assertEquals('パスワードを入力してください', $errors->first('password'));
    }

    // ログイン情報がない場合のバリデーション
    public function test_user_not_registered()
    {
        $response = $this->post('/login', [
            'email' => 'invalid@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);
        $errors = session('errors');
        $this->assertEquals('ログイン情報が登録されていません', $errors->first('email'));
    }

}
