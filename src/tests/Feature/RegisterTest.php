<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    //正常な登録のテスト
    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'TestUser',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
        ]);
    }

    // 名前のバリデーション
    public function test_name_is_required()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['name']);
        $errors = session('errors');
        $this->assertEquals('お名前を入力してください', $errors->first('name'));
    }

    // メールアドレスのバリデーション
    public function test_email_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'TestUser',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);
        $errors = session('errors');
        $this->assertEquals('メールアドレスを入力してください', $errors->first('email'));
    }

    // パスワードのバリデーション
    public function test_password_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'TestUser',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
        $errors = session('errors');
        $this->assertEquals('パスワードを入力してください', $errors->first('password'));
    }

    // パスワードが7文字以下
    public function test_password_must_be_at_least_8_characters()
    {
        $response = $this->post('/register', [
            'name' => 'TestUser',
            'email' => 'test@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);

        $response->assertSessionHasErrors(['password']);
        $errors = session('errors');
        $this->assertEquals('パスワードは8文字以上で入力してください', $errors->first('password'));
    }

    // パスワード確認と不一致
    public function test_password_confirmation_must_match()
    {
        $response = $this->post('/register', [
            'name' => 'TestUser',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors(['password']);
        $errors = session('errors');
        $this->assertEquals('パスワードと一致しません', $errors->first('password'));
    }
}
