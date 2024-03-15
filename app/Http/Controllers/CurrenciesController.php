<?php

namespace App\Http\Controllers;
use App\Models\CurrenciesByDate;
use App\Models\CurrenciesValue;
use DateTime;
use DateTimeZone;


class CurrenciesController extends Controller
{
    public function today()
    {
        $moscowDateTime = new DateTime("now", new DateTimeZone('Europe/Moscow'));
        $today = $moscowDateTime->format('Y-m-d');

        try {
            $currenciesByDate = CurrenciesByDate::where('status', CurrenciesByDate::STATUS_CORRECT)
                ->where('date', $today)
                ->firstOrFail();
        } catch (\Exception $e) {
            return response(['error'=>'No data'], 503);
        }
        // Тут намеренно сделан второй селект вместо ->with(), тк указание колонок дб при использовании with работает не очевидно
        $currenciesValues = CurrenciesValue::where('currencies_by_date_id', $currenciesByDate->id)
            ->get(['num_code','v_unit_rate'])
            ->all();

        return [
            'date' => $today,
            'data' => $currenciesValues,
        ];
    }
}
