<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProfileController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ItemController::class, 'index'])->name('item-index');
Route::get('/item/{id}', [ItemController::class, 'show'])->name('item-detail');




Route::middleware(['auth'])->group(function () {
    // メール確認待ち画面
    Route::get('/email/verify', function () {
        return view('auth.verify-email'); // ビューを自作する
    })->name('verification.notice');

    // メール内のリンクをクリックした時
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/mypage/profile'); // 認証後のリダイレクト先
    })->middleware(['signed'])->name('verification.verify');

    // 再送信ボタン
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '認証メールを再送しました！');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// 決済後・決済不可戻り用ルート
Route::get(
    '/purchase/success',
    [PurchaseController::class, 'success']
)->name('purchase.success');
Route::get(
    '/purchase/cancel',
    [PurchaseController::class, 'cancel']
)->name('purchase.cancel');


Route::middleware(['auth','verified'])->group(function () {
    Route::get('/mypage/profile', [AuthController::class, 'index'])->name('profile-edit');
    Route::post('/mypage/profile/update', [AuthController::class, 'update'])->name('profile-update');
    Route::get('/sell', [ItemController::class, 'create'])->name('sell.create');
    Route::post('/sell/store', [ItemController::class, 'store'])->name('sell.store');

    Route::post('/item/{item}/like', [LikeController::class, 'store'])->name('items.like');
    Route::delete('/item/{item}/like', [LikeController::class, 'destroy'])->name('items.unlike');

    Route::post('/item/{item}/comments',[CommentController::class,'store'])->name('comments.store');

    Route::get('/purchase/{item}', [PurchaseController::class,'create'])->name('purchase.create');
    Route::post('/purchase/{item}', [PurchaseController::class,'store'])->name('purchase.store');

    Route::get('/purchase/address/{item}', [ProfileController::class, 'editAddress'])->name('profile.address.edit');
    Route::post('/purchase/address/{item}', [ProfileController::class, 'updateAddress'])->name('profile.address.update');

    route::get('/mypage',[ProfileController::class,'index'])->name('profile.index');
});
