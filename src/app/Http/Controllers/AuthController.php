<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $profile = $user->profile;

        return view('user.profile-edit', compact('user', 'profile'));
    }


    public function update(ProfileRequest $request)
    {
        //userの呼び出し
        $user = auth()->user();

        //user名の更新
        $user->name = $request->input('name');
        $user->save();

        // profiles に入れるデータ（画像以外）
        $profileData = [
            'postcode'  => $request->input('postcode'),
            'address'   => $request->input('address'),
            'building'  => $request->input('building'),
        ];

        // 画像がある時だけ img_url を追加
        if ($request->hasFile('img_url')) {
            $path = $request->file('img_url')->store('public/profile_images');
            $filename = basename($path);
            $profileData['img_url'] = 'storage/profile_images/' . $filename;
        }

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return redirect()->route('profile-edit')->with('success', 'プロフィールを更新しました。');
    }
}
