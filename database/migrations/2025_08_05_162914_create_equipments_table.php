<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('name');
            $table->string('serial_number')->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('last_maintenance')->nullable();
            $table->date('next_maintenance')->nullable();
            $table->enum('status', ['نشطة', 'تحت الصيانة', 'معطلة'])->default('نشطة');

            $table->string('type')->nullable();
            $table->string('location')->nullable(); 
            $table->enum('usage_hours', [
                '0.5', '1', '1.5', '2', '2.5', '3', '3.5', '4', '4.5', '5',
                '6', '7', '8', '9', '10', '12', '24'
            ])->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
