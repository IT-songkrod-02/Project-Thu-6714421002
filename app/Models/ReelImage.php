<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReelImage extends Model
{
    protected $fillable = ['reel_id', 'image_path', 'is_primary'];
    protected $casts = ['is_primary' => 'boolean'];

    public function reel() { return $this->belongsTo(Reel::class); }
    public function getUrlAttribute(): string { return asset('storage/' . $this->image_path); }
}