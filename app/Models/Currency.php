<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['code', 'name'];

    public function cashboxes()
    {
        return $this->hasMany(Cashbox::class);
    }

    public function exchangeRates()
    {
        return $this->hasMany(ExchangeRate::class, 'currency_code', 'code');
    }
}
