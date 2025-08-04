<?php

namespace App\Repositories;

use App\Models\Cashbox;
use App\Models\ExchangeRate;
use App\Models\Record;

class AnalyticRepository
{
    public function getRecord($cashboxId, $from, $to)
    {

        $query = Record::query();
        if ($cashboxId)
            $query->where('cashbox_id', $cashboxId);

        if ($from && $to)
            $query->whereBetween('date', [$from, $to]);

        return $query;
    }

    public function getExchangeRates()
    {
        $data = ExchangeRate::select('currency_code', 'rate')->get();
        return $data;
    }

    public function getQueryCloneIncome($query, $code)
    {
        $incomeRaw = (clone $query)->where('type', 1)->where('currency', $code)->sum('amount');
        return $incomeRaw;
    }

    public function getQueryCloneExpense($query, $code)
    {
        $expenseRaw = (clone $query)->where('type', 0)->where('currency', $code)->sum('amount');
        return $expenseRaw;
    }

    public function getExchangeRateTm()
    {
        $data = ExchangeRate::select('rate')->where('currency_code', 'TMT')->first();
        return $data;
    }

    public function getAmountOfEachCashbox($tmtRate)
    {
        $data = Record::select('cashbox_id')
            ->selectRaw("ROUND(SUM(CASE WHEN type = 1 THEN amount / exchange_rate * $tmtRate ELSE -amount / exchange_rate * $tmtRate END),2) as balance")
            ->groupBy('cashbox_id')
            ->with('cashbox:id,title')
            ->get();

        return $data;
    }
}
