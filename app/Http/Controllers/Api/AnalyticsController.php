<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Cashbox\AnalyticResource;
use App\Models\Cashbox;
use App\Models\ExchangeRate;
use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnalyticsController extends Controller
{
    public function index()
    {
        $income = Record::where('type', 1)->sum('amount');
        $expense = Record::where('type', 0)->sum('amount');
        $balance = $income - $expense;

        $byCashboxes = Cashbox::with('currency')
            ->withSum(['records as income_sum' => fn($q) => $q->where('type', 1)], 'amount')
            ->withSum(['records as expense_sum' => fn($q) => $q->where('type', 0)], 'amount')
            ->get();

        return response()->json([
            'income' => $income,
            'expense' => $expense,
            'balance' => $balance,
            'cashboxes' => $byCashboxes,
        ]);
    }

    public function summary(Request $request)
    {
        $cashboxId = $request->input('cashbox_id');
        $from = $request->input('from');
        $to = $request->input('to');

        $query = Record::query();

        if ($cashboxId) {
            $query->where('cashbox_id', $cashboxId);
        }

        if ($from && $to) {
            $query->whereBetween('date', [$from, $to]);
        }
        $exchangeRates = ExchangeRate::select('currency_code', 'rate')->get();

        $totalIncome = 0;
        $totalExpense = 0;

        foreach ($exchangeRates as $exchangeRate) {
            $code = $exchangeRate->currency_code;
            $rate = $exchangeRate->rate;

            $incomeRaw = (clone $query)->where('type', 1)->where('currency', $code)->sum('amount');
            $expenseRaw = (clone $query)->where('type', 0)->where('currency', $code)->sum('amount');

            $incomeInUSD = $incomeRaw / $rate;
            $expenseInUSD = $expenseRaw / $rate;

            $totalIncome += $incomeInUSD;
            $totalExpense += $expenseInUSD;
        }
        $exchangeRateTMT = ExchangeRate::select('rate')->where('currency_code', 'TMT')->first();
        $tmtRate = $exchangeRateTMT ? $exchangeRateTMT->rate : 19.75;

        $income = round($totalIncome * $tmtRate, 2);
        $expense = round($totalExpense * $tmtRate, 2);

        $byCashbox = Record::select('cashbox_id')
            ->selectRaw("ROUND(SUM(CASE WHEN type = 1 THEN amount / exchange_rate * 19.75 ELSE -amount / exchange_rate * 19.75 END),2) as balance")
            ->groupBy('cashbox_id')
            ->with('cashbox:id,title')
            ->get();
        // $analyticsCashbox = AnalyticResource::collection(Cashbox::with('records')->get());

        // Log::info('TEST---:' . $analyticsCashbox->toJson());

        return response()->json([
            'income' => $income,
            'expense' => $expense,
            'balance' => round($income - $expense, 2),
            'by_cashbox' => $byCashbox,
        ]);
    }
}
