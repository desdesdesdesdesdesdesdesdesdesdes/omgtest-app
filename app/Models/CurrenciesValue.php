<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurrenciesValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'num_code', 'v_unit_rate',
    ];

    public function currenciesByDate(): BelongsTo
    {
        return $this->belongsTo(CurrenciesByDate::class);
    }
}
