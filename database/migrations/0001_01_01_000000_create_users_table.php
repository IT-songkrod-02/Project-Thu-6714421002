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
Schema::create('users', function (Blueprint $table) {
            $table->id();                                              // 1
            $table->string('name');                                    // 2
            $table->string('email')->unique();                         // 3
            $table->string('password');                                // 4
            $table->string('phone_number', 20)->nullable();            // 5
            $table->text('address')->nullable();                       // 6
            $table->string('profile_image')->nullable();               // 7
            $table->string('line_id', 100)->nullable();                // 8
            $table->string('facebook_url')->nullable();                // 9
            $table->decimal('seller_rating', 3, 2)->default(0);        // 10
            $table->enum('role', ['admin', 'user'])->default('user');  // 11
            $table->boolean('is_verified_seller')->default(false);     // เพิ่ม: Verified Badge
            $table->rememberToken();
            $table->timestamps();                                      // 12
});
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
