<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies_values', function (Blueprint $table) {
            $table->id();
            $table->integer('num_code')
                ->comment('ISO Цифр. код валюты');
            $table->string('v_unit_rate')
                ->comment('Курс за 1 единицу валюты');
            $table->integer('currencies_by_date_id');
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
        Schema::dropIfExists('currencies_values');
    }
}
