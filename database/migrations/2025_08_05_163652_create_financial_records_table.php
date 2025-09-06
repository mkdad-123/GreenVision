<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('financial_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->enum('direction', ['دخل', 'نفقات']);
            $table->enum('category', [
                'دعم زراعي',
                'أسمدة',
                'مبيدات',
                'بذور',
                'وقود',
                'مياه',
                'صيانة',
                'معدات',
                'عمالة',
                'أخرى'
            ]);

            $table->float('amount');
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('reference_number')->nullable();


            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_records');
    }
};

