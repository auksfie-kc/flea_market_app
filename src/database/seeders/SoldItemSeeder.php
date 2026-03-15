<?php

namespace Database\Seeders;

use App\Models\SoldItem;
use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Seeder;

class SoldItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // まだ売れていない商品から3件だけ取得
        $items = Item::doesntHave('soldItem')
            ->inRandomOrder()
            ->take(3)
            ->get();

        foreach ($items as $item) {
            // profileを持っていて、出品者本人ではないユーザーを購入者にする
            $buyer = User::whereHas('profile')
                ->where('id', '!=', $item->user_id)
                ->inRandomOrder()
                ->first();

            // 条件を満たす購入者がいなければスキップ
            if (!$buyer || !$buyer->profile) {
                continue;
            }

            SoldItem::create([
                'item_id' => $item->id,
                'user_id' => $buyer->id,
                'sold_postcode' => $buyer->profile->postcode,
                'sold_address' => $buyer->profile->address,
                'sold_building' => $buyer->profile->building,
                'payment_method' => collect([
                    'credit_card',
                    'convenience_store',
                ])->random(),
                'status' =>'pending',
                'stripe_checkout_session_id' => null,
            ]);
        }
    }
}
