<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Models\Profile;
use App\Models\Item;
use App\Models\SoldItem;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function editAddress(Item $item)
    {
        $user = auth()->user();
        $profile = $user->profile;

        return view('user.address', compact('user','item', 'profile'));
    }

    public function updateAddress(AddressRequest $request, Item $item)
    {
        $user = auth()->user();


        Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'postcode' => $request->input('postcode'),
                'address' => $request->input('address'),
                'building' => $request->input('building'),
            ]
        );

        return redirect()->route('purchase.create', ['item' => $item->id])->with('success', '住所を更新しました。');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;
        $tab  = $request->query('tab', 'sell'); // デフォルトはsell

        switch ($tab) {
            case 'buy':
                $items = $user->soldItems()
                    ->with('item')
                    ->latest()
                    ->paginate(12, ['*'], 'buy');
                break;

            case 'sell':
            default:
                $tab = 'sell';
                $items = $user->items()
                    ->latest()
                    ->paginate(12, ['*'], 'sell');
                break;
        }

        return view('user.profile', compact('user', 'profile', 'tab', 'items'));
    }
}
