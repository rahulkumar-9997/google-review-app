<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewShop extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'address',
        'google_review_url',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(ReviewQuestion::class)->orderBy('sort_order');
    }
}