<?php

namespace App\Repositories;


use App\Models\Record;

class RecordRepository
{
    public function getRecords(): object
    {
        $records = Record::with('cashbox')->orderByDesc('date')->get();

        return $records;
    }
}
