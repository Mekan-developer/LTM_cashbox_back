<?php

namespace App\Services;

use App\Models\ExchangeRate;
use App\Models\Record;
use App\Repositories\AnalyticRepository;

class AnalyticService
{

    public function __construct(protected AnalyticRepository $repository) {}

    public function getAnalyticSummary($request)
    {

        $cashboxId = $request->input('cashbox_id');
        $from = $request->input('from');
        $to = $request->input('to');
        $query = $this->repository->getRecord($cashboxId, $from, $to);


        $exchangeRates = $this->repository->getExchangeRates();

        $totalIncome = 0;
        $totalExpense = 0;
        foreach ($exchangeRates as $exchangeRate) {
            $code = $exchangeRate->currency_code;
            $rate = $exchangeRate->rate;

            $incomeRaw = $this->repository->getQueryCloneIncome($query, $code);
            $expenseRaw = $this->repository->getQueryCloneExpense($query, $code);

            $incomeInUSD = $incomeRaw / $rate;
            $expenseInUSD = $expenseRaw / $rate;

            $totalIncome += $incomeInUSD;
            $totalExpense += $expenseInUSD;
        }
        $exchangeRateTMT = $this->repository->getExchangeRateTm();
        $tmtRate = $exchangeRateTMT ? $exchangeRateTMT->rate : 19.5;

        $income = round($totalIncome * $tmtRate, 2);
        $expense = round($totalExpense * $tmtRate, 2);

        $byCashbox = $this->repository->getAmountOfEachCashbox($tmtRate);

        return [
            'income' => $income,
            'expense' => $expense,
            'balance' => round($income - $expense, 2),
            'by_cashbox' => $byCashbox,
        ];
    }
}
