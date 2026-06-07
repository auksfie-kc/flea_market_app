@extends('layouts.auth')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush

@section('content')

<div class="register-form__content">

    @if ($errors->any())
    <ul class="register-form__error-list">
        @foreach ($errors->all() as $error)
        <li>{{$error}}</li>
        @endforeach
    </ul>
    @endif

    <div class="register-form__heading">
        <h1>会員登録</h1>
    </div>

    <form class="form" action="{{ route('register') }}" method="post" novalidate>
        @csrf

        <!--名前-->
        <div class="form__group">
            <label class="form__label--item" for="name">ユーザー名</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}">
        </div>

        <!--メールアドレス-->
        <div class="form__group">
            <label class="form__label--item" for="email">メールアドレス</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}">
        </div>

        <!--パスワード-->
        <div class="form__group">
            <label class="form__label--item" for="password">パスワード</label>
            <input type="password" name="password" id="password">
        </div>

        <!--確認用パスワード-->
        <div class="form__group">
            <label class="form__label--item" for="password_confirmation">確認用パスワード</label>
            <input type="password" name="password_confirmation" id="password_confirmation">
        </div>

        <!--登録ボタン-->
        <div class="form__button">
            <button class="form__button-submit" type="submit">登録する</button>
        </div>
    </form>

    <div class="register-form__footer">
        <p><a href="{{ route('login') }}">ログインはこちら</a></p>
    </div>

</div>
@endsection