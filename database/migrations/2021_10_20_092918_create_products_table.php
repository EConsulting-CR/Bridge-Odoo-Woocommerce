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
            $table->bigInteger('odoo_id')->unique();
            $table->bigInteger('wc_id')->nullable()->unique();
            $table->string('sku');
            $table->string('name');
            $table->double('price',2);
            $table->boolean('sale_ok');
            $table->boolean('sync')->default(0);
            $table->dateTime('sync_date')->nullable();
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
