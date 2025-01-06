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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['labor', 'service', 'part'])->nullable();
            $table->string('identification_code')->nullable();
            $table->string('upc')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('manufacturer')->nullable();

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
        Schema::dropIfExists('products');
    }
};
