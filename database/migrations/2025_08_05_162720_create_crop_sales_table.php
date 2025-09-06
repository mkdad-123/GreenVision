<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('crop_sales', function (Blueprint $table) {
            $table->id(); 

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('farm_id')->constrained()->onDelete('cascade');

            $table->string('crop_name');
            $table->float('quantity');
            $table->enum('unit', ['كغ', 'طن', 'صندوق', 'ربطة', 'علبة'])->default('كغ');

            $table->float('price_per_unit');
            $table->float('total_price');

            $table->date('sale_date');
            $table->enum('status', ['تم البيع', 'قيد البيع', 'محجوز'])->default('قيد البيع');
            $table->string('buyer_name')->nullable();
            $table->string('delivery_location')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crop_sales');
    }
};

