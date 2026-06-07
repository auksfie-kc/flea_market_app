@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endpush

@section('content')

<div class="purchase-container">

    @if (session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
    @endif


    @if ($errors->any())
    <ul class="purchase-errors">
        @foreach ($errors->all() as $error)
        <li class="purchase-errors__item">{{ $error }}</li>
        @endforeach
    </ul>
    @endif


    <div class="purchase-item">


        <!-- 購入フォーム -->
        <form action="{{ route('purchase.store', ['item' => $item->id]) }}" method="POST" novalidate>
            @csrf


            <!-- 商品情報 -->
            <div class="purchase-item__details">

                <!-- 商品画像 -->
                <div class="purchase-item__image">
                    @if(!empty($item->img_url))
                    <img src="{{ asset($item->img_url) }}" alt="{{ $item->name }}">
                    @else
                    <div class="purchase-item__noimage">No Image</div>
                    @endif
                </div>

                <div class="purchase-item__info">
                    <h1 class="purchase-item__name">{{ $item->name }}</h1>
                    <p class="purchase-item__item-price">¥{{ number_format($item->price) }}</p>
                </div>
            </div>


            <!-- 支払い方法 -->
            <div class="purchase-item__payment">

                <h2>支払い方法</h2>

                <select name="payment_method"
                    id="payment_method"
                    class="purchase-item__payment-select">

                    <option value="">選択してください</option>

                    <option value="credit_card">
                        クレジットカード
                    </option>

                    <option value="convenience_store">
                        コンビニ払い
                    </option>

                </select>

            </div>


            <!-- 配送先 -->
            <div class="purchase-item__address">

                <h2>配送先住所</h2>

                <p class="purchase-item__postcode">
                    〒{{ $user->profile->postcode ?? '未設定' }}
                </p>

                <p class="purchase-item__address-text">
                    {{ $user->profile->address ?? '未設定' }}
                </p>

                <p class="purchase-item__building">
                    {{ $user->profile->building ?? '未設定' }}
                </p>

            </div>


            <!-- 住所変更 -->
            <div class="purchase-item__change-address">

                <a href="{{ route('profile.address.edit', ['item' => $item->id]) }}"
                    class="purchase-item__change-address-link">

                    変更する

                </a>

            </div>


            <!-- 価格まとめ -->
            <div class="purchase-item__total">

                <h2>商品代金</h2>
                <p class="purchase-item__price">
                    ¥{{ number_format($item->price) }}
                </p>

                <h2>支払い方法</h2>
                <p class="purchase-item__payment-method"></p>

            </div>


            <!-- 購入ボタン -->
            <div class="purchase-item__actions">

                <button type="submit"
                    class="purchase-item__confirm-button">

                    購入する

                </button>

            </div>

        </form>

    </div>

</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {

        const select = document.getElementById('payment_method');
        const display = document.querySelector('.purchase-item__payment-method');

        select.addEventListener('change', function() {

            const selectedText = select.options[select.selectedIndex].text;

            display.textContent = selectedText;

        });

    });
</script>


@endsection