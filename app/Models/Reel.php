<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reel extends Model
{
    public const TYPES = [
        'spinning'    => 'สปินนิ่ง',
        'baitcasting' => 'เบทคาสติ้ง',
        'trolling'    => 'ทรอลิ่ง / โอเวอร์เฮด',
        'spincast'    => 'สปินคาสท์',
        'fly'         => 'ฟลายรีล',
        'jigging'     => 'จิ๊กกิ้ง',
    ];

    protected $fillable = [
        'user_id', 'brand_id', 'title', 'model_name', 'reel_type', 'price',
        'condition_percentage', 'gear_ratio', 'ball_bearings', 'max_drag_kg',
        'weight_g', 'description', 'location_province', 'location_district',
        'view_count', 'status',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function brand() { return $this->belongsTo(Brand::class); }
    public function images() { return $this->hasMany(ReelImage::class); }
    public function primaryImage() { return $this->hasOne(ReelImage::class)->where('is_primary', true); }
    public function favoritedBy() { return $this->belongsToMany(User::class, 'favorites'); }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->reel_type] ?? $this->reel_type;
    }

    public function getCoverUrlAttribute(): string
    {
        if ($this->primaryImage) {
            return asset('storage/' . $this->primaryImage->image_path);
        }
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="600" height="450"><rect width="100%" height="100%" fill="#FAF6EA"/><text x="50%" y="50%" fill="#D4AF37" font-size="28" font-family="serif" text-anchor="middle">NO IMAGE</text></svg>';
        return 'data:image/svg+xml;utf8,' . rawurlencode($svg);
    }
}