<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\SoldItem;
use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function create(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        return view('user.purchase', compact('item', 'user'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        $profile = $user->profile;
        // 住所未登録なら弾く
        if (!$profile || !$profile->postcode || !$profile->address) {
            return redirect()
                ->route('profile.edit')
                ->with('error', '住所を登録してください。');
        }

        // すでに売れているなら弾く
        if ($item->soldItem()->exists()) {
            return back()->withErrors(['item_id' => 'この商品は売り切れです。']);
        }

            $sold = SoldItem::create([
                'item_id'   => $item->id,
                'user_id'   => $user->id,
                'sold_postcode' => $user->profile->postcode,
                'sold_address'  => $user->profile->address,
                'sold_building' => $user->profile->building ?? null,
                'payment_method' => $request['payment_method'],
                'status' => 'pending', // 購入後は一旦pendingにして、後で購入完了にする
                'stripe_checkout_session_id' => null,
            ]);


        //コンビニ払いならここで終了
        if ($request->payment_method === 'convenience_store') {
        return redirect()->route('item-index')->with('success', '購入(コンビニ払い)を受付しました！');
        }

        //クレカならStripe Checkoutへ遷移
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success'),
            'cancel_url'  => route('purchase.cancel'),
        ]);

        // Stripe CheckoutセッションIDをSoldItemに保存
        $sold->update(['stripe_checkout_session_id' => $session->id]);

        // Stripe Checkoutへリダイレクト
        return redirect($session->url);
    }


    public function success()
    {
        return redirect()->route('item-index')->with('success', '決済画面から戻りました。');
    }

    public function cancel()
    {
        return redirect()->route('item-index')->with('error', '決済をキャンセルしました。');
    }
}
