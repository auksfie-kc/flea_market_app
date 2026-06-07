<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class VerifyEmailTest extends TestCase
{
    use RefreshDatabase;

    // 認証メール送信
    public function test_verification_email_is_sent_after_register()
    {
        // Notificationファサードを使用して、通知が送信されたかどうかの確認.実際には送らない
        Notification::fake();
        // 未認証のユーザーを作成
        $user = User::factory()->unverified()->create();
        // 認証メールを送信
        $user->sendEmailVerificationNotification();
        // VerifyEmail通知がユーザーに送信されたことを確認
        Notification::assertSentTo(
            $user,
            VerifyEmail::class
        );
    }

    //認証誘導画面にてmailtrapへの遷移
    public function test_verify_email_button_redirects_to_mailtrap()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/email/verify');

        $response->assertStatus(200);

        $response->assertSee('https://mailtrap.io/inboxes');
        $response->assertSee('認証はこちらから');
    }

    //認証後のリダイレクト先がマイページプロフィールであることの確認
    public function test_user_is_redirected_to_profile_after_email_verification()
    {
        // 未認証のユーザーを作成
        $user = User::factory()->unverified()->create();

        // 認証URL作成
        $verificationUrl = URL::temporarySignedRoute(

            // メール内のリンクをクリックした時のルート
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );
        // 認証URLにアクセス
        $response = $this->actingAs($user)
            ->get($verificationUrl);

        // 認証後のリダイレクト先がマイページプロフィールであることを確認
        $response->assertRedirect('/mypage/profile');

        // ユーザーのメールが認証されていることを確認
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }
}
