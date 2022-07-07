<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id('invoice_detail_id');
            $table->bigInteger('invoice_id')->unsigned();
            $table->foreign('invoice_id')->references('invoice_id')->on('invoice_masters');
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('product_id')->on('product_masters');
            $table->double('rate',8,2);
            $table->double('unit',8,2);
            $table->double('qty',8,2);
            $table->double('disc_per',8,2);
            $table->double('net_amount',8,2);
            $table->double('total_amount',8,2);
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
        Schema::dropIfExists('invoice_details');
    }
}
