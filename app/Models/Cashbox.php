<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cashbox extends Model
{
    protected $fillable = ['title', 'currency_id', 'description'];
    protected $hidden = ['created_at', 'updated_at'];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function records()
    {
        return $this->hasMany(Record::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'cashbox_user');
    }
}
