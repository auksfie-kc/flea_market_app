<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Item;


class LikeController extends Controller
{
    public function store(Item $item)
    {
        if (!$item->isLikedBy(auth()->user())) { //ログインユーザーがまだこの商品をいいねしていなければ、いいねを追加する。
            $item->likes()->create([
                'user_id' => auth()->id(),
            ]);
        }
        return back();
    }

    public function destroy(Item $item)
    {
        $item->likes()->where('user_id', auth()->id())->delete(); //すでにこの商品にいいねしていれば削除する。
        return back();
    }
}
