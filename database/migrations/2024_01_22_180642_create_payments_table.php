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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // foreign
            $table->unsignedBigInteger('payment_type_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            // foreign

            $table->float('amount', 10, 2)->nullable();
            $table->string('authorization_approval_code')->nullable();
            $table->string('credit_card_number')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('security_code')->nullable();

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
        Schema::dropIfExists('payments');
    }
};
