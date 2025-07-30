<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $fillable = [
        'cashbox_id',
        'type',
        'is_debt',
        'article_type',
        'article_description',
        'original_amount',
        'original_currency',
        'amount',
        'currency',
        'exchange_rate',
        'date',
        'link',
        'object'
    ];

    protected $casts = [
        'is_debt' => 'boolean',
        'type' => 'boolean',
        'date' => 'date',
    ];

    public function cashbox()
    {
        return $this->belongsTo(Cashbox::class);
    }
}
