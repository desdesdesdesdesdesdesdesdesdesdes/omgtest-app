<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use App\Models\CurrenciesByDate;
use datetime;
use DateTimeZone;



class CurrenciesTest extends TestCase
{
    use RefreshDatabase;

    public function test_today_no_data()
    {
        $response = $this->get('/api/currencies');
        $response->assertStatus(503);
    }
    public function test_today_incorrect_data()
    {
        $moscowDateTime = new DateTime("now", new DateTimeZone('Europe/Moscow'));
        $today = $moscowDateTime->format('Y-m-d');

        $currenciesByDate = CurrenciesByDate::create([
            'date' => '2024-03-03',
            'status' => 1,
        ], [
            'date' => $today,
            'status' => 2,
        ]);

        $response = $this->get('/api/currencies');
        $response->assertStatus(503);
    }
    public function test_today_correct_data()
    {
        $moscowDateTime = new DateTime("now", new DateTimeZone('Europe/Moscow'));
        $today = $moscowDateTime->format('Y-m-d');

        $currenciesByDate = CurrenciesByDate::create([
            'date' => $today,
            'status' => 1,
        ]);

        $response = $this->get('/api/currencies');
        $response->assertStatus(200);
    }
    public function test_today_integration()
    {
        Http::fake([
            config('sbrfApi.currencies_daily_url') .'*' => Http::response(Storage::disk('local')->get('xsd/sbrf/Valuta.xml'), 200),
        ]);

        Artisan::call('currenciesData:update');
        $this->assertGreaterThan(0, CurrenciesByDate::count());

        $moscowDateTime = new DateTime("now", new DateTimeZone('Europe/Moscow'));
        $today = $moscowDateTime->format('Y-m-d');

        $response = $this->get('/api/currencies');
        $response
            ->assertStatus(200)
            ->assertJson([
                'date' => $today,
            ]);
        // вообще тут должна проверяться структура и наличие данных, но идея и так понятна
    }

}
