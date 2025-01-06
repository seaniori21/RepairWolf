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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('no')->nullable();
            $table->date('order_date')->nullable();
            // refs
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->unsignedBigInteger('cashier_id')->nullable();
            $table->unsignedBigInteger('service_person_id')->nullable();
            // refs
            $table->float('tax', 10, 2)->nullable();
            $table->float('convenience_fee', 10, 2)->nullable();
            $table->float('discount', 10, 2)->nullable();

            $table->float('base_total', 10, 2)->nullable();
            $table->float('list_total', 10, 2)->nullable();
            $table->float('grand_total', 10, 2)->nullable();
            $table->float('paid_amount', 10, 2)->nullable();
            $table->float('due_amount', 10, 2)->nullable();

            $table->string('status')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
