@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/sell-item.css') }}">
@endpush

@section('content')
<div class="sell-item">

    @if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif

    <form action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <h1 class="sell-item__title">商品の出品</h1>
        <p class="sell-item__image">商品の画像</p>
        <input type="file" name="img_url" id="img_url" class="sell-item__image-input">
        <img id="preview" src="" alt="画像プレビュー" width="200" height="130" style="display:none;">
        <label for="img_url" class="sell-item__image-label"></label>

        <!-- JavaScript部分 -->
        <script>
            const imageInput = document.getElementById('img_url');
            const preview = document.getElementById('preview');
            const imageLabel = document.querySelector('.sell-item__image-label');

            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                        imageLabel.style.display = 'none';
                    }

                    reader.readAsDataURL(file);
                }
            });
        </script>


        <h2 class="sell-item__title">商品の詳細</h2>

        <div class="sell-item__category-title">カテゴリー</div>
        @foreach($categories as $category)
        <label class="sell-item__category-label">
            <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" class="sell-item__category-checkbox">
            <span class="sell-item__category-text">{{ $category->name }}</span>
        </label>
        @endforeach

        <div class="sell-item__condition-title">商品の状態</div>
        <select class="sell-item__condition-select" name="condition_id">
            <option value="">選択してください</option>
            @foreach($conditions as $condition)
            <option value="{{ $condition->id }}">{{ $condition->condition }}</option>
            @endforeach
        </select>

        <h2 class="sell-item__title">商品名と説明</h2>

        <div class="sell-item__name-title">商品名</div>
        <input type="text" name="name" class="sell-item__name-input">

        <div class="sell-item__brand-title">ブランド名</div>
        <input type="text" name="brand" class="sell-item__brand-input">

        <div class="sell-item__description-title">商品の説明</div>
        <textarea name="description" class="sell-item__description-textarea"></textarea>

        <div class="sell-item__price-title">販売価格</div>
        <div class="sell-item__price-box">
            <span class="sell-item__price-yen">¥</span>
            <input type="number" name="price" class="sell-item__price-input">
        </div>

        <button type="submit" class="sell-item__submit-button">出品する</button>

    </form>
</div>

@endsection