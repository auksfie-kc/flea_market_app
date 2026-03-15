<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price','img_url' ,'brand','condition_id', 'user_id'];


    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_items');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // いいねしたユーザーを取得
    public function isLikedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->likes()
            ->where('user_id', $user->id)
            ->exists();
    }


    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

        public function soldItem()
        {
            return $this->hasOne(SoldItem::class);
        }
}

