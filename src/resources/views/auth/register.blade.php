@extends('layouts.auth')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush

@section('content')

<div class="register-form__content">

    @if (count($errors) > 0)
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{$error}}</li>
        @endforeach
    </ul>
    @endif

    <div class="register-form__heading">
        <h2>会員登録</h2>
    </div>

    <form class="form" action="/register" method="post">
        @csrf

        <!--名前-->
        <div class="form__group">
            <label class="form__label--item">ユーザー名</label>
            <input type="text" name="name" value="{{ old('name') }}">
        </div>

        <!--メールアドレス-->
        <div class="form__group">
            <label class="form__label--item">メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}">
        </div>

        <!--パスワード-->
        <div class="form__group">
            <label class="form__label--item">パスワード</label>
            <input type="password" name="password">
        </div>

        <!--確認用パスワード-->
        <div class="form__group">
            <label class="form__label--item">確認用パスワード</label>
            <input type="password" name="password_confirmation">
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