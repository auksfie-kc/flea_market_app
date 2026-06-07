@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/item-list.css') }}">
@endpush

@section('content')
<div class="item-list__header">

    @if (session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
    @endif

    {{-- ===== タブ ===== --}}
    <div class="item-list__tabs">
        <a href="{{ route('item-index', ['tab' => 'all', 'keyword' => request('keyword')]) }}"
            class="item-list__tab {{ $tab === 'all' ? 'is-active' : '' }}">
            おすすめ
        </a>
        <a href="{{ route('item-index', ['tab' => 'mylist', 'keyword' => request('keyword')]) }}"
            class="item-list__tab {{ $tab === 'mylist' ? 'is-active' : '' }}">
            マイリスト
        </a>
    </div>

    <h1 class="visually-hidden">商品一覧</h1>

    <ul class="item-list">
        @forelse ($items as $item)
        <li class="item-list__item">
            <a href="{{ route('item-detail', ['id' => $item->id]) }}">
                <div class="item-card">
                    <div class="item-card__image">
                        @if(!empty($item->img_url))
                        <img src="{{ asset($item->img_url) }}" alt="{{ $item->name }}">
                        @else
                        <div class="item-card__noimage">No Image</div>
                        @endif

                        @if($item->soldItem)
                        <span class="item-card__sold-badge">Sold</span>
                        @endif
                    </div>

                    <div class="item-card__body">
                        <h2 class="item-card__name">{{ $item->name }}</h2>
                    </div>
                </div>
            </a>
        </li>
        @empty
        {{-- 何も出さない --}}
        @endforelse

    </ul>

    {{-- ページネーション --}}
    <div class="pagination">
        {{ $items->appends(['tab' => $tab])->links() }}
    </div>
</div>
@endsection