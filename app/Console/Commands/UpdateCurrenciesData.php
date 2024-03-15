<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use datetime;
use DateTimeZone;
use App\Services\SbrfService;



class UpdateCurrenciesData extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    // Date format Y-m-d
    protected $signature = 'currenciesData:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store everyday values os SBRF currencies';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(SbrfService $SbrfService):void
    {
        $moscowDateTime = new DateTime("now", new DateTimeZone('Europe/Moscow'));

        $SbrfService->getCurrenciesByDate($moscowDateTime->format('Y-m-d'));

        // SBRF выдает курсы валют на следующий день до 12 часов дня по Москве.
        if($moscowDateTime->format('H')>=12) {
            // Получаем курсы на завтра
            $moscowDateTime->modify('+1 day');
        }
        $SbrfService->getCurrenciesByDate($moscowDateTime->format('Y-m-d'));
    }

}
