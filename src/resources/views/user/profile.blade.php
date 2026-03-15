@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
<div class="profile__container">

    <div class="profile__image">
        @if(!empty($profile->img_url))
        <img src="{{ asset($profile->img_url) }}" alt="{{ $user->name }}のプロフィール画像" width="200" height="200">
        @else
        <img src="{{ asset('images/default.png') }}" alt="デフォルトプロフィール画像" width="200" height="200">
        @endif
    </div>
    <div class="profile__details">
        <h2>{{ $user->name }}</h2>
    </div>
    <div class="profile__edit">
        <a href="{{ route('profile-edit') }}" class="profile__edit-link">プロフィールを編集</a>
    </div>

    {{-- ===== タブ ===== --}}
    <div class="profile__tabs">
        <a href="{{ route('profile.index', ['tab' => 'sell']) }}"
            class="profile__tab {{ $tab === 'sell' ? 'is-active' : '' }}">
            出品した商品
        </a>
        <a href="{{ route('profile.index', ['tab' => 'buy']) }}"
            class="profile__tab {{ $tab === 'buy' ? 'is-active' : '' }}">
            購入した商品
        </a>
    </div>

    {{-- ===== 一覧 ===== --}}
    <div class="profile__content">

        {{-- 出品一覧 --}}
        @if($tab === 'sell')

        @if($items->count())
        <div class="profile__grid">
            @foreach($items as $item)
            <a href="{{ route('item-detail', ['id' => $item->id]) }}" class="profile__card">

                <div class="profile__card-image">
                    @if(!empty($item->img_url ?? null))
                    <img src="{{ asset($item->img_url) }}" alt="{{ $item->name ?? '商品画像' }}">
                    @else
                    <img src="{{ asset('images/default_item.png') }}" alt="商品画像">
                    @endif
                </div>

                <div class="profile__card-body">
                    <div class="profile__card-name">{{ $item->name ?? '商品名' }}</div>
                </div>
            </a>
            @endforeach
        </div>

        {{ $items->appends(['tab' => 'sell'])->links() }}
        @else
        <p class="profile__empty">出品した商品はまだありません。</p>
        @endif
        @endif

        {{-- 購入一覧（sold_items -> item を表示する想定） --}}
        @if($tab === 'buy')

        @if($items->count())
        <div class="profile__grid">
            @foreach($items as $sold)

            @if($sold->item)
            <a href="{{ route('item-detail', ['id' => $sold->item->id]) }}" class="profile__card">

                <div class="profile__card-image">
                    @if(!empty($sold->item->img_url ?? null))
                    <img src="{{ asset($sold->item->img_url) }}" alt="{{ $sold->item->name ?? '商品画像' }}">
                    @else
                    <img src="{{ asset('images/default_item.png') }}" alt="商品画像">
                    @endif
                </div>

                <div class="profile__card-body">
                    <div class="profile__card-name">{{ $sold->item->name ?? '商品名' }}</div>
                </div>
            </a>
            @endif
            @endforeach
        </div>

        {{ $items->appends(['tab' => 'buy'])->links() }}
        @else
        <p class="profile__empty">購入した商品はまだありません。</p>
        @endif
        @endif
    </div>
</div>
@endsection