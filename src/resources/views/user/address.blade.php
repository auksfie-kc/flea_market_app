@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endpush

@section('content')

<div class="address__form">
    <div class="address-form__heading">
        <h2>住所の変更</h2>
    </div>
    <div class="address-form__body">

        @if ($errors->any())
        <div class="alert-error">
            入力にエラーがあります。再度ご確認ください。
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('profile.address.update',$item->id) }}" method="POST" novalidate>
            @csrf
            <div class="address-form__group">
                <label for="postcode">郵便番号</label>
                <input type="text" id="postcode" name="postcode" value="{{ old('postcode', optional($profile)->postcode) }}" required>
            </div>

            <div class="address-form__group">
                <label for="address">住所</label>
                <input type="text" id="address" name="address" value="{{ old('address', optional($profile)->address) }}" required>
            </div>

            <div class="address-form__group">
                <label for="building">建物名・部屋番号</label>
                <input type="text" id="building" name="building" value="{{ old('building', optional($profile)->building) }}">
            </div>

            <button type="submit" class="address-form__submit-button">更新する</button>
        </form>
    </div>
</div>
    @endsection