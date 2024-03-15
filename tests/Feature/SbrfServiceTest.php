<?php

namespace Tests\Feature;

use App\Models\CurrenciesByDate;
use App\Services\SbrfService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use datetime;
use DateTimeZone;

class SbrfServiceTest extends TestCase
{
    use RefreshDatabase;
    private $SbrfService;

    public function setUp(): void
    {
        parent::setUp();

        $this->SbrfService = $this->app->make(SbrfService::class);
    }
    public function test_getCurrenciesByDate() {
        Http::fake([
            config('sbrfApi.currencies_daily_url') .'*' => Http::response(Storage::disk('local')->get('xsd/sbrf/Valuta.xml'), 200),
        ]);

        $moscowDateTime = new DateTime("now", new DateTimeZone('Europe/Moscow'));
        $today = $moscowDateTime->format('Y-m-d');
        $currenciesByDate = $this->SbrfService->getCurrenciesByDate($today);
        $this->assertEquals(CurrenciesByDate::STATUS_CORRECT, $currenciesByDate->status);
    }

}
