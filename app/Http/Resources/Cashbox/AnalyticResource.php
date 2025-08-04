<?php

namespace App\Http\Resources\Cashbox;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnalyticResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $totalBalance = $this->records->reduce(function ($carry, $record) {
            $amount = (float) $record->amount / $record->exchange_rate;
            if ($record->type == 1) {
                return $carry + $amount;
            } else { // type == 0
                return $carry - $amount;
            }
        }, 0);

        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'records' => [
                'cashbox_id' => $this->id,
                'balance' => number_format($totalBalance, 2, '.', ''), // например "1181.11"
            ],
        ];
        return $data;
    }
}
