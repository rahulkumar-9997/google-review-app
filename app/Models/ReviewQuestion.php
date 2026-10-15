<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ReviewQuestion extends Model
{
    protected $fillable = [
        'review_shop_id',
        'key',
        'question',
        'type',
        'placeholder',
        'is_required',
        'max_select',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'max_select' => 'integer',
        'sort_order' => 'integer',
    ];

    public function shop()
    {
        return $this->belongsTo(ReviewShop::class, 'review_shop_id');
    }

    public function options()
    {
        return $this->hasMany(ReviewQuestionOption::class)->orderBy('sort_order');
    }

    public function activeOptions()
    {
        return $this->options()->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}