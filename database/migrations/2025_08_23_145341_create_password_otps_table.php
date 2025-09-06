<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('password_otps', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('code_hash');           // تخزين الكود مُشفَّر (مو نصيًا)
            $table->timestamp('expires_at');
            $table->unsignedSmallInteger('attempts')->default(0); // عدد المحاولات
            $table->timestamp('used_at')->nullable();
            $table->string('ip', 45)->nullable();  // IPv4/IPv6
            $table->string('user_agent', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_otps');
    }
};
