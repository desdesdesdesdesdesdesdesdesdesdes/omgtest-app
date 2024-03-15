<?php

return [
    'currencies_daily_url' => env('SBRF_API_currencies_daily_url', 'https://www.cbr.ru/scripts/XML_daily.asp?date_req='),
    // Curl retry
    'retry_on_request_times' => env('SBRF_API_retry_on_request_times', 2),
    'retry_timeout' => env('SBRF_API_retry_timeout', 5000),
];
