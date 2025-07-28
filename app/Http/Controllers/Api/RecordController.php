<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RecordRequest;
use App\Models\Cashbox;
use App\Models\ExchangeRate;
use App\Models\Record;
use Illuminate\Support\Facades\Log as FacadesLog;

class RecordController extends Controller
{
    public function index()
    {
        return Record::with('cashbox')->orderByDesc('date')->get();
    }

    public function store(RecordRequest $request)
    {

        // 🧠 Получаем кассу и её валюту
        $cashbox = Cashbox::findOrFail($request['cashbox_id']);
        $cashboxCurrency = $cashbox->currency->code;

        // 🧠 Получаем курс обмена (можно улучшить по дате)
        $rate = ExchangeRate::where('currency_code', $request['original_currency'])->latest()->first();
        $exchangeRate = $rate ? $rate->rate : 1;

        // 💰 Конвертируем сумму в валюту кассы
        $convertedAmount = $request['original_amount'] * $exchangeRate;

        // Расчёт суммы в валюте кассы
        // $amount = $request['original_amount'] * $rate;
        $validated = $request->validated();
        FacadesLog::info('test validated data: ', $validated);
        $validated['currency'] = $cashboxCurrency;
        $validated['exchange_rate'] = $exchangeRate;
        $validated['amount'] = $convertedAmount;
        $record = Record::create($validated,);


        return response()->json($record, 201);;
    }

    public function show(Record $record)
    {
        return $record;
    }

    public function destroy(Record $record)
    {
        $record->delete();
        return response()->noContent();
    }
}
