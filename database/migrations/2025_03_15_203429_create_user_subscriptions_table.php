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
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('subscription_id');
            $table->double('price')->default(0);
            $table->timestamp('expire_date')->nullable();
            $table->string('payment_gateway')->nullable();
            $table->string('payment_status')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('transaction_id')->nullable();
            $table->string('manual_payment_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
