@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile-edit.css') }}">
@endpush

@section('content')

<div class="profile__form">
    <div class="profile-form__heading">
        <h2>プロフィール設定</h2>
    </div>
    <div class="profile-form__body">

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

        @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('profile-update') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="profile-form__group_image">
                <input type="file" id="img_url" name="img_url" class="profile-form__image-input" accept="image/png, image/jpeg">
                <img id="preview" src="{{ optional($profile)->img_url ? asset($profile->img_url) : asset('images/default.png') }}" alt="プロフィール画像" width="200" height="200" style="display:block;">
                <label for="img_url">画像を選択する</label>

                <!-- JavaScript部分 -->
                <script>
                    const imageInput = document.getElementById('img_url'); // ファイル選択の要素
                    const preview = document.getElementById('preview'); // プレビュー画像表示の要素

                    imageInput.addEventListener('change', function() {
                        const file = this.files[0]; // 選んだファイルを取得
                        if (file) {
                            const reader = new FileReader(); // ファイル読み込み用のオブジェクト

                            reader.onload = function(e) {
                                preview.src = e.target.result; // 画像データを表示
                                preview.style.display = 'block'; // 表示する
                            }

                            reader.readAsDataURL(file); // ファイルを読み込む
                        }
                    });
                </script>
                <div class="profile-form__group">
                    <label for="name">ユーザー名</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="profile-form__group">
                    <label for="postcode">郵便番号</label>
                    <input type="text" id="postcode" name="postcode" value="{{ old('postcode', optional($profile)->postcode) }}" required>
                </div>

                <div class="profile-form__group">
                    <label for="address">住所</label>
                    <input type="text" id="address" name="address" value="{{ old('address', optional($profile)->address) }}" required>
                </div>

                <div class="profile-form__group">
                    <label for="building">建物名</label>
                    <input type="text" id="building" name="building" value="{{ old('building', optional($profile)->building) }}">
                </div>
            </div>
            <button type="submit" class="profile-form__submit">更新する</button>
        </form>

    </div>
    @endsection