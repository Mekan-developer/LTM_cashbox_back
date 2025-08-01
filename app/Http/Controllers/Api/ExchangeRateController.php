<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    public function index()
    {
        return ExchangeRate::orderBy('date', 'desc')->get();
    }

    public function store(Request $request)
    {

        $request->validate([
            'currency_code' => 'required|string',
            'rate' => 'required|numeric',
            'date' => 'nullable|date',
        ]);






        return ExchangeRate::create($request->all());
    }

    public function destroy(ExchangeRate $exchangeRate)
    {
        $exchangeRate->delete();
        return response()->noContent();
    }
}
