<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
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
<<<<<<< HEAD
            $table->string('name', 255);
            $table->string('barcode', 25)->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('stock');
            $table->integer('alerts');
            $table->string('image', 100)->nullable();
=======
            $table->string('name',255);
            $table->string('barcode',25)->nullable();
            $table->decimal('cost',10,2)->default(0);
            $table->decimal('price',10,2)->default(0);
            $table->integer('stock');
            $table->integer('alerts');
            $table->string('image',100)->nullable();
>>>>>>> 315cc16c0b22309447497a0584b4df3ab55431d3

            $table->unsignedBigInteger('category_id');           
            $table->foreign('category_id')->references('id')->on('categories');

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
}

