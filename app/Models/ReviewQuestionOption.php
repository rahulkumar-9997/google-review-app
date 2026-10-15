<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewQuestionOption extends Model
{
    protected $fillable = [
        'review_question_id',
        'option_text',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function question()
    {
        return $this->belongsTo(ReviewQuestion::class, 'review_question_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}