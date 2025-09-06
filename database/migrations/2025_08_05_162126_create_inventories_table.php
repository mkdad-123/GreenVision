<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id(); // المفتاح الأساسي

            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ربط بالمستخدم الذي يملك هذا المخزون

            $table->string('name'); // اسم المادة الزراعية
            $table->string('type'); // نوع المادة (سماد، مبيد، بذور...)
            $table->float('quantity')->default(0); // الكمية المتوفرة
            $table->enum('unit', ['كغ', 'جرام', 'لتر', 'مل', 'كيس', 'علبة', 'قارورة', 'حبة'])->default('كغ');
            $table->date('purchase_date')->nullable(); // تاريخ الشراء (اختياري)
            $table->date('expiry_date')->nullable(); // تاريخ انتهاء الصلاحية (اختياري)
            $table->float('min_threshold')->default(0); // الحد الأدنى المسموح به قبل التنبيه

            $table->string('supplier')->nullable(); // اسم المورد (اختياري)
            $table->string('storage_location')->nullable(); // مكان التخزين (مخزن 1، قبو...)
            $table->text('notes')->nullable(); // ملاحظات إضافية عن المادة

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};

