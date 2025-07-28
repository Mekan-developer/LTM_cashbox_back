<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        return response()->json(Currency::with('exchangeRates')->get(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:currencies',
            'name' => 'required',
        ]);

        return Currency::create($request->only('code', 'name'));
    }

    public function show(Currency $currency)
    {
        return $currency;
    }

    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'code' => 'required|unique:currencies,code,' . $currency->id,
            'name' => 'required',
        ]);

        $currency->update($request->only('code', 'name'));
        return $currency;
    }

    public function destroy(Currency $currency)
    {
        $currency->delete();
        return response()->noContent();
    }
}
