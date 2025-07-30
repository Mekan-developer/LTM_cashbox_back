<?php

namespace App\Http\Resources\Currency;

use App\Http\Resources\Cashbox\IndexResource as CashboxIndexResource;
use App\Http\Resources\ExchangeRate\IndexResource as ExchangeRateIndexResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'name' => $this->name,
            'code' => $this->code,
            'exchangeRates' => ExchangeRateIndexResource::collection($this->exchangeRates),
        ];
        return $data;
    }
}
