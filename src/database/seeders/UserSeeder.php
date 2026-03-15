<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 出品テストユーザー１件を作成
        $testUser = User::create([
            'name' => 'test_user',
            'email' => 'test_user@example.com',
            'password' => Hash::make('password'),
        ]);

        // このtest_user に profile を紐付けて作成
        Profile::create([
            'user_id'  => $testUser->id,
            'postcode' => '123-4567',
            'address'  => '京都府',
            'building' => 'マンション111',
            'img_url'  => null,
        ]);

        // 購入テストユーザー１件を作成
        $testBuyer = User::create([
            'name' => 'test_buyer',
            'email' => 'test_buyer@example.com',
            'password' => Hash::make('password'),
        ]);

        // このtest_buyer に profile を紐付けて作成
        Profile::create([
            'user_id'  => $testBuyer->id,
            'postcode' => '987-6543',
            'address'  => '大阪府',
            'building' => 'テストビル222',
            'img_url'  => null,
        ]);

        // 10人のダミーユーザーを作成
        User::factory()->count(10)->create();
    }
}
