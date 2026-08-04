<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * sales テーブルは Order / OrderItem に統合されたため廃止。
 * user_id を持たず注文単位の概念も無い旧実装で、呼び出し元のUIも存在しなかった。
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('sales');
    }

    public function down(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('total_price');
            $table->timestamps();
        });
    }
};
