<?php

namespace App\Http\Resources\Record;

use App\Http\Resources\Cashbox\IndexResource as CashboxIndexResource;
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
            'id' => $this->id,
            'cashbox_id' => $this->cashbox_id,
            'type' => $this->type == 1 ? 'income' : 'expense',
            'is_debt' => $this->is_debt,
            'article_type' => $this->article_type, //dumayu ne nuzhen eto
            'article_description' => $this->article_description,
            'original_amount' => $this->original_amount,
            'original_currency' => $this->original_currency,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'exchange_rate' => $this->exchange_rate,
            'date' => $this->date->format('Y-m-d'),
            'link' => $this->link,
            'object' => $this->object,
            'cashbox' => $this->cashbox
        ];
        return $data;
    }
}
