<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SellItemRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;




class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'all');
        $keyword = $request->query('keyword');

        // マイリスト（会員のみ）
        if ($tab === 'mylist') {
            if (!Auth::check()) {
                return redirect()->route('login');
            }
            $user = Auth::user();
            $items = $user->likedItems()
                ->when(!empty($keyword), function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                })
                ->latest()
                ->paginate(12)
                ->appends($request->query());

            return view('user.item-list', compact('items', 'tab'));
        }


        // 全商品
        $query = Item::select(['id', 'name', 'img_url', 'created_at'])
            ->latest();

        // ログインしている場合、自分の商品を除外
        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }

        // 商品名の部分一致検索
        if (!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        $items = $query->paginate(8)->appends($request->query());

        return view('user.item-list', compact('items', 'tab'));
    }

    public function show($id)
    {
        $item = Item::with(['categories', 'condition'])
        ->withCount('comments') // コメント数
        ->findOrFail($id);

        $likesCount = $item->likes()->count();       // いいねの数を取得
        $isLiked   = $item->isLikedBy(auth()->user()); // ログインユーザーがこの商品をいいねしているかどうかを取得

        // コメントをページネーション（10件ずつ）
        $comments = $item->comments()
            ->with('user')
            ->latest()
            ->paginate(5);
        return view('user.item-detail', compact('item', 'likesCount', 'isLiked', 'comments'));
    }

    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view('user.sell-item', compact('categories', 'conditions'));
    }

    public function store(SellItemRequest $request)
    {

        $filename = null;
        if ($request->hasFile('img_url')) {
            $path = $request->file('img_url')->store('public/item_images');
            $filename = basename($path);
        }

        $item = Item::create([

            'user_id' => auth()->id(),
            'condition_id' => $request->input('condition_id'),
            'name' => $request->input('name'),
            'img_url' => $filename ? 'storage/item_images/' . $filename : null,
            'brand' => $request->input('brand'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
        ]);
        $item->categories()->sync($request->category_ids);

        return redirect()->route('item-index')->with('success', '商品を出品しました！');
    }
}
