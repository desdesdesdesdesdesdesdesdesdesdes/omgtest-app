<?php

namespace App\Services;

use App\Models\CurrenciesByDate;
use Carbon\Doctrine\CarbonType;
use Illuminate\Support\Facades\Http;
use DOMDocument;

class SbrfService
{
    private string $valutaXsdPath = 'storage/app/xsd/sbrf/Valuta.xsd';

    public function getCurrenciesByDate(string $date): CurrenciesByDate
    {
        $currenciesByDate = CurrenciesByDate::firstOrCreate([
            'date' => $date,
        ]);

        if($currenciesByDate->status == CurrenciesByDate::STATUS_CORRECT) {
            return $currenciesByDate;
        }

        $url = config('sbrfApi.currencies_daily_url') . date('d/m/Y', strtotime($date));
        try {
            $xml = $this->apiCall($url, $this->valutaXsdPath);
        } catch(\Exception $e) {
            $currenciesByDate->status = CurrenciesByDate::STATUS_FAILED;
            $currenciesByDate->save();

            throw $e;
        }
        $items = $xml->getElementsByTagName('Valute');
        $currenciesValues = [];
        foreach ($items as $item) {
            $currenciesValues[] = [
                'num_code' => $item->getElementsByTagName('NumCode')->item(0)->nodeValue,
                'v_unit_rate' => $item->getElementsByTagName('VunitRate')->item(0)->nodeValue,
            ];
        }

        $currenciesByDate->currenciesValues()->delete();
        $currenciesByDate->currenciesValues()->createMany($currenciesValues);

        $currenciesByDate->status = CurrenciesByDate::STATUS_CORRECT;
        $currenciesByDate->save();

        $this->getCurrenciesByDateCleanUp($currenciesByDate);

        return $currenciesByDate;
    }

    protected function getCurrenciesByDateCleanUp(currenciesByDate $currenciesByDate):void
    {
        $outdatedCurrenciesByDates = CurrenciesByDate::where('id', '<', $currenciesByDate->id-1)->get();
        foreach($outdatedCurrenciesByDates as $outdatedCurrenciesByDate) {
            $outdatedCurrenciesByDate->currenciesValues()->delete();
            $outdatedCurrenciesByDate->delete();
        }
    }

    protected function apiCall($url, $xsdPath): DOMDocument
    {
        $response = Http::retry(config('sbrfApi.retry_on_request_times'), config('sbrfApi.retry_timeout'))->get($url);
        if($response->status()!==200) {
            throw new \Exception('SBRF network error '. $response->status());
        }

        $xml = new DOMDocument();
        $xml->loadXML($response->body());
        $xml->schemaValidate($xsdPath);

        return $xml;
    }

}
