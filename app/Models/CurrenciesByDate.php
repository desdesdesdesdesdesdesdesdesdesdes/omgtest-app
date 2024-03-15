<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CurrenciesByDate extends Model
{
    use HasFactory;

    public const STATUS_PROCESSING = 0;
    public const STATUS_CORRECT = 1;
    public const STATUS_FAILED = 2;

    protected $fillable = [
        'date', 'status',
    ];

    public function currenciesValues(): HasMany
    {
        return $this->hasMany(CurrenciesValue::class);
    }
}
