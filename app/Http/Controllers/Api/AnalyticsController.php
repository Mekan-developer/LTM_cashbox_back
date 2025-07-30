<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cashbox;
use App\Models\Record;
use Illuminate\Http\Request;

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

        $income = (clone $query)->where('type', 1)->sum('amount');
        $expense = (clone $query)->where('type', 0)->sum('amount');

        $byCashbox = Record::select('cashbox_id')
            ->selectRaw("SUM(CASE WHEN type = 1 THEN amount ELSE -amount END) as balance")
            ->groupBy('cashbox_id')
            ->with('cashbox:id,title')
            ->get();

        return response()->json([
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
            'by_cashbox' => $byCashbox,
        ]);
    }
}
