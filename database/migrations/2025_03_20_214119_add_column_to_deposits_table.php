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
        Schema::table('deposits', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('subscription_id')->after('user_id');
            $table->string('payment_gateway')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            //
            $table->dropColumn('subscription_id');
            $table->dropColumn('payment_gateway');
        });
    }
};
