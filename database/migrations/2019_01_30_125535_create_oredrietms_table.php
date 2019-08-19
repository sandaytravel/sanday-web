<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOredrietmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oredrietms', function (Blueprint $table) {
            $table->primary('id');
            $table->integer('order_id', 11);
            $table->integer('package_id', 11);
            $table->integer('package_quantity_id', 11);
            $table->integer('quantity', 11);
            $table->string('total_price',255);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oredrietms');
    }
}
