<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('reels', function (Blueprint $table) {
        $table->id();                                                          // 15
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();        // 16
        $table->foreignId('brand_id')->constrained()->restrictOnDelete();      // 17
        $table->string('title', 150);                                          // 18
        $table->string('model_name', 100);                                     // 19
        $table->string('reel_type', 30);                                       // 20
        $table->decimal('price', 12, 2);                                       // 21
        $table->unsignedTinyInteger('condition_percentage');                   // 22
        $table->string('gear_ratio', 20)->nullable();                          // 23
        $table->unsignedTinyInteger('ball_bearings')->nullable();              // 24
        $table->decimal('max_drag_kg', 5, 2)->nullable();                      // 25
        $table->unsignedSmallInteger('weight_g')->nullable();                  // 26
        $table->text('description')->nullable();                               // 27
        $table->string('location_province', 100);                              // 28
        $table->string('location_district', 100)->nullable();                  // 29
        $table->unsignedInteger('view_count')->default(0);                     // 30
        $table->enum('status', ['available', 'sold', 'hidden'])->default('available'); // 31
        $table->timestamps();                                                  // 32

        $table->index(['status', 'reel_type']);
        $table->index('price');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reels');
    }
};
