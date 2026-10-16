<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone_number', 'address', 'profile_image',
        'line_id', 'facebook_url', 'seller_rating', 'role', 'is_verified_seller',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_verified_seller' => 'boolean',
            'seller_rating' => 'float',
        ];
    }

    public function reels() { return $this->hasMany(Reel::class); }
    public function favorites() { return $this->belongsToMany(Reel::class, 'favorites')->withTimestamps(); }
    public function reviewsReceived() { return $this->hasMany(Review::class, 'seller_id'); }
    public function isAdmin(): bool { return $this->role === 'admin'; }
}