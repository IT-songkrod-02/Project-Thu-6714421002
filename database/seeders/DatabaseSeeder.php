<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Reel;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(BrandSeeder::class);

        User::firstOrCreate(['email' => 'admin@reel.test'], [
            'name' => 'Administrator', 'password' => 'password',
            'role' => 'admin', 'is_verified_seller' => true,
        ]);

        $seller = User::firstOrCreate(['email' => 'seller@reel.test'], [
            'name' => 'คุณสมชาย นักตกปลา', 'password' => 'password',
            'phone_number' => '0812345678', 'line_id' => 'somchai.reel',
            'is_verified_seller' => true, 'seller_rating' => 4.8,
        ]);

        $demo = [
            ['Shimano', 'Stella SW 8000HG', 'spinning', 28500, 95, '5.6:1', 13, 25, 505, 'Stella SW สภาพสวยมาก ใช้งานน้อย'],
            ['Daiwa', 'Steez A TW 1016SV-SH', 'baitcasting', 9800, 90, '7.1:1', 10, 6, 190, 'รอกเบทตีเหยื่อปลอม สภาพดี เบรกลื่น'],
            ['Penn', 'Fathom 25 Lever Drag', 'trolling', 12900, 85, '4.1:1', 4, 20, 960, 'สำหรับตกปลาทะเลลึก มีรอยใช้งานเล็กน้อย'],
        ];
        foreach ($demo as [$brand, $model, $type, $price, $cond, $gear, $bb, $drag, $w, $desc]) {
            Reel::firstOrCreate(['title' => "$brand $model", 'user_id' => $seller->id], [
                'brand_id' => Brand::where('name', $brand)->value('id'),
                'model_name' => $model, 'reel_type' => $type, 'price' => $price,
                'condition_percentage' => $cond, 'gear_ratio' => $gear, 'ball_bearings' => $bb,
                'max_drag_kg' => $drag, 'weight_g' => $w, 'description' => $desc,
                'location_province' => 'ระยอง', 'location_district' => 'เมืองระยอง',
            ]);
        }
    }
}