<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['seller_id', 'reviewer_id', 'rating', 'comment'];

    public function reviewer() { return $this->belongsTo(User::class, 'reviewer_id'); }
}