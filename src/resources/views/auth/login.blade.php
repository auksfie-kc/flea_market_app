@extends('layouts.auth')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')

<div class="login-form__content">

    @if ($errors->any())
    <ul class="login-form__error-item">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif
    <div class="login-form__heading">
        <h1>ログイン</h1>
    </div>


    <form class="form" action="{{ route('login') }}" method="post" novalidate>
        @csrf


        <!--メールアドレス-->
        <div class="form__group-title">
            <label class="form__label--item" for="email">メールアドレス</label>
            <div class="form__input-text">
                <input type="email" name="email" id="email" value="{{ old('email') }}">
            </div>
        </div>

        <!--パスワード-->
        <div class="form__group-title">
            <label class="form__label--item" for="password">パスワード</label>
            <div class="form__input-text">
                <input type="password" name="password" id="password">
            </div>
        </div>


        <!--ログインボタン-->
        <div class="form__button">
            <button class="form__button-submit" type="submit">ログインする</button>
        </div>
    </form>
    <div class="login-form__footer">
        <p><a href="{{ route('register') }}">会員登録はこちら</a></p>
    </div>

</div>
@endsection