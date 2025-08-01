<?php

namespace App\Services;

use App\Repositories\RecordRepository;
use App\Http\Resources\Record\IndexResource as RecordIndexResource;
use App\Models\Cashbox;
use App\Models\ExchangeRate;
use App\Models\Record;
use App\Repositories\CashboxRepository;
use Illuminate\Support\Facades\Log as FacadesLog;
use Log;

class RecordService
{

    public function __construct(protected RecordRepository $repository, protected CashboxRepository $cashboxRepository) {}

    public function getRecords(): array
    {
        $records = $this->repository->getRecords();
        $records = RecordIndexResource::collection($records);
        $data = $records->resolve(); //wrapping without {'data':[{},{}]};

        return $data;
    }

    public function storeRecord($request): object
    {
        $relativeCurrency = "USD";
        // 🧠 Получаем курс обмена (можно улучшить по дате)
        $rate = ExchangeRate::where('currency_code', $request['original_currency'])->latest()->first();
        $exchangeRate = $rate ? $rate->rate : 1;

        // 🧠 Получаем кассу и её валюту
        $cashbox = $this->cashboxRepository->getCashboxById($request['cashbox_id']);
        $cashboxCurrencyCode = $cashbox->currency->code;

        $cashboxRate = ExchangeRate::where('currency_code', $cashboxCurrencyCode)->latest()->first();
        $cashboxCurrencyRate = $cashboxRate->rate;

        //wichisleniye amount
        $originalCurrency = $request['original_currency'];
        $originalAmount = $request['original_amount'];
        if ($originalCurrency === $cashboxCurrencyCode) {
            $convertedAmount = $originalAmount;
        } else {
            if ($cashboxCurrencyCode === $relativeCurrency) {
                // Валюта кассы указана как базовая в курсе, просто делим
                $convertedAmount = $originalAmount / $exchangeRate;
            } else {
                // Валюта кассы НЕ указана как базовая — пересчитываем через её курс
                $convertedAmount = ($cashboxCurrencyRate / $exchangeRate) * $originalAmount;
            }
        }
        //wichislenie amount end

        $validated = $request->validated();
        $validated['currency'] = $cashboxCurrencyCode;
        $validated['exchange_rate'] = $cashboxCurrencyRate;
        $validated['amount'] = $convertedAmount;
        $record = Record::create($validated);
        $recordWithCashbox = $record->load('cashbox');

        return $recordWithCashbox;
    }
}
