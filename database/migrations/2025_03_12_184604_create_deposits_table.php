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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('method_code');
            $table->float('amount',28,8)->default(0);
            $table->string('method_currency')->nullable();
            $table->float('charge',28,8)->default(0);
            $table->float('rate',28,8)->default(0);
            $table->float('final_amount',28,8)->default(0);
            $table->text('detail')->nullable();
            $table->string('btc_amount')->nullable();
            $table->string('btc_walet')->nullable();
            $table->string('trx')->nullable();
            $table->integer('payment_try')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('from_api')->default(0);
            $table->string('admin_feedback')->nullable();
            $table->string('success_url')->nullable();
            $table->string('failed_url')->nullable();
            $table->integer('last_cron')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
