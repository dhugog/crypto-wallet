<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCryptoPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crypto_prices', function (Blueprint $table) {
            $table->id();
            $table->char('cryptocurrency', 3)->default('BTC');
            $table->char('currency', 3)->default('BRL');
            $table->decimal('buy', 4, 3);
            $table->decimal('sell', 4, 3);
            $table->timestamps();

            $table->foreign('cryptocurrency')->references('code')->on('currencies');
            $table->foreign('currency')->references('code')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crypto_prices');
    }
}
