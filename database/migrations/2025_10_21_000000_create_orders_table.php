<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('total_price');                     // 合計金額
            $table->string('status')->default('paid');          // 注文ステータス（paid / pending / cancelled）
            $table->string('payment_method');                   // 支払い方法（cod / credit_card）
            $table->string('shipping_name');                    // お届け先氏名
            $table->string('shipping_zip')->nullable();         // 郵便番号
            $table->string('shipping_address');                 // 配送先住所
            $table->string('shipping_tel')->nullable();         // 電話番号
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
