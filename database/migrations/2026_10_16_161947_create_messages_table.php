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
    Schema::create('messages', function (Blueprint $table) {
        $table->id();                                                              // 38
        $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();   // 39
        $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete(); // 40
        $table->foreignId('reel_id')->constrained()->cascadeOnDelete();            // 41
        $table->text('message_text');                                              // 42
        $table->boolean('is_read')->default(false);                                // 43
        $table->timestamps();                                                      // 44

        $table->index(['reel_id', 'sender_id', 'receiver_id']);
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
