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
        $income = Record::where('type', 'income')->sum('amount');
        $expense = Record::where('type', 'expense')->sum('amount');
        $balance = $income - $expense;

        $byCashboxes = Cashbox::with('currency')
            ->withSum(['records as income_sum' => fn($q) => $q->where('type', 'income')], 'amount')
            ->withSum(['records as expense_sum' => fn($q) => $q->where('type', 'expense')], 'amount')
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

        $income = (clone $query)->where('type', 'income')->sum('amount');
        $expense = (clone $query)->where('type', 'expense')->sum('amount');

        $byCashbox = Record::select('cashbox_id')
            ->selectRaw("SUM(CASE WHEN type = 'income' THEN amount ELSE -amount END) as balance")
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
