<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payer_id')->unsigned();
            $table->foreign('payer_id')
                ->references('id')
                ->on('users');
            $table->integer('payee_id')->unsigned();
            $table->foreign('payee_id')
                ->references('id')
                ->on('users');
            $table->integer('payment_type_id');
            $table->decimal('value');
            $table->decimal('discount');
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
        Schema::dropIfExists('transactions');
    }
}
