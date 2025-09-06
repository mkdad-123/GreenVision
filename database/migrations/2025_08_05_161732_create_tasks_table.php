<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('farm_id')->constrained()->onDelete('cascade');

            $table->string('type');
            $table->text('description')->nullable();
            $table->date('date');
            $table->enum('repeat_interval', [
                'يومي',
                'يومين',
                'ثلاث ايام',
                'اربع ايام',
                'خمس ايام',
                'اسبوعي',
                'شهري',
                'سنوي'
            ])->nullable();
            $table->enum('status', ['قيد التنفيذ', 'منجزة', 'مؤجلة'])->default('قيد التنفيذ');
            $table->enum('priority', ['عالية', 'متوسطة', 'منخفضة'])->default('متوسطة')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
