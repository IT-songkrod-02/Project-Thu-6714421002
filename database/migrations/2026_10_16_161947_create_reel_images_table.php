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
    Schema::create('reel_images', function (Blueprint $table) {
        $table->id();                                                     // 33
        $table->foreignId('reel_id')->constrained()->cascadeOnDelete();   // 34
        $table->string('image_path');                                     // 35
        $table->boolean('is_primary')->default(false);                    // 36
        $table->timestamps();                                             // 37
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reel_images');
    }
};
