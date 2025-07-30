<?php

namespace App\Http\Resources\ExchangeRate;

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
            'currency_code' => $this->currency_code,
            'rate' => $this->rate,
            'date' => $this->date->format('Y-m-d'),
        ];
        return $data;
    }
}
