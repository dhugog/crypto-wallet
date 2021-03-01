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
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->char('credited_currency', 3)->nullable();
            $table->bigInteger('credited_amount')->unsigned()->nullable();
            $table->char('debited_currency', 3)->nullable();
            $table->bigInteger('debited_amount')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('credited_currency')->references('code')->on('currencies');
            $table->foreign('debited_currency')->references('code')->on('currencies');
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
