<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            // refs
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->uuid('scheduled_notify_id')->nullable();
            $table->timestamp('scheduled_notify_at')->nullable();
            $table->tinyInteger('notification_sent')->default(0);
            // refs
            $table->integer('quantity')->nullable();
            $table->float('base_price', 10, 2)->nullable();
            $table->float('list_price', 10, 2)->nullable();
            $table->tinyInteger('trash')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_products');
    }
};
