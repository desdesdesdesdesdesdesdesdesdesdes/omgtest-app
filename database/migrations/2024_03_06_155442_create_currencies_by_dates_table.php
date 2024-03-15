<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesByDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies_by_dates', function (Blueprint $table) {
            $table->id();
            $table->date('date')
                ->unique()
                ->comment('Дата на которую значение актуально');
            $table->integer('status')
                ->default(0);
            $table->integer('retry_count')
                ->default(0);
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
        Schema::dropIfExists('currencies_by_dates');
    }
}
