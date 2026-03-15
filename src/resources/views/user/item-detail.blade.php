@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/item-detail.css') }}">
@endpush

@section('content')
<div class="item-detail">
    <div class="item-detail__image">
        @if(!empty($item->img_url))
        <img src="{{ asset($item->img_url) }}" alt="{{ $item->name }}">
        @else
        <div class="item-detail__noimage">No Image</div>
        @endif
    </div>

    <div class="item-detail__info">
        <h1 class="item-detail__name">{{ $item->name }}</h1>
        <p class="item-detail__brand">{{ $item->brand }}</p>
        <p class="item-detail__price">¥{{ number_format($item->price) }} <span>(税込)</span></p>

        <div class="item-detail__action">
            <div class="item-detail__action-left">
                @auth
                @if($isLiked)
                <form action="{{ route('items.unlike', $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="item-detail__icon-button">♡</button>
                </form>
                @else
                <form action="{{ route('items.like', $item->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="item-detail__icon-button">♡</button>
                </form>
                @endif
                @else
                <button class="item-detail__icon-button item-detail__icon-button--disabled" type="button">♡</button>
                @endauth
                <p class="item-detail__count">{{ $item->likes->count() }}</p>
            </div>

            <div class="item-detail__action-right">
                <span class="item-detail__icon">💬</span>
                <p class="item-detail__count">{{ $item->comments_count }}</p>
            </div>
        </div>

        @if($item->soldItem)
        <button class="item-detail__purchase-button item-detail__purchase-button--disabled" disabled>
            売り切れ
        </button>
        @else
        <form action="{{ route('purchase.create', $item->id) }}" method="GET">
            <button class="item-detail__purchase-button">購入手続きへ</button>
        </form>
        @endif


        <h2 class="item-detail__description-title">商品説明</h2>
        <p class="item-detail__description">{{ $item->description }}</p>
        <h2 class="item-detail__information-title">商品情報</h2>

        <div class="item-detail__meta">
            <h3 class="item-detail__category-title">カテゴリー</h3>
            <p class="item-detail__category">
                @foreach ($item->categories as $category)
                <span class="item-detail__category-tag">{{ $category->name }}</span>
                @endforeach
            </p>
        </div>

        <div class="item-detail__meta">
            <h3 class="item-detail__condition-title">商品の状態</h3>
            <p class="item-detail__condition">
                <span class="item-detail__condition-tag">{{ $item->condition->condition }}</span>
            </p>
        </div>

        <h3 class="item-detail__comments-title">コメント（{{ $item->comments_count }}）</h3>

        <div class="item-detail__comments">
            @foreach ($comments as $comment)
            <div class="item-detail__comment">
                <p class="item-detail__comment-user">{{ $comment->user->name }}</p>
                <p class="item-detail__comment-body">{{ $comment->comment }}</p>
            </div>
            @endforeach

            {{-- もっと見る（次ページがある時だけ表示） --}}
            @if ($comments->hasMorePages())
            <div class="item-detail__more">
                <a href="{{ $comments->nextPageUrl() }}">もっと見る</a>
            </div>
            @endif
        </div>
        <div class="item-detail__comment-form">
            <div class="item-detail__comment-form-title">商品へのコメント</div>

            @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            @endif

            <form action="{{ route('comments.store', $item) }}" method="POST" novalidate>
                @csrf
                <textarea name="comment" class="item-detail__textarea" rows="5" required></textarea>
                <button type="submit" class="item-detail__comment-button">コメントを送信する</button>
            </form>
        </div>
    </div>
</div>
@endsection