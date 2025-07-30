<?php

namespace App\Http\Resources\Cashbox;

use App\Http\Resources\Currency\IndexResource as CurrencyIndexResource;
use App\Http\Resources\Record\IndexResource as RecordIndexResource;
use App\Http\Resources\User\IndexResource as UserIndexResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    // public static $wrap = 'data2';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $data = [
            'title' => $this->title,
            'currency_id' => $this->currency_id,
            'description' => $this->description,
            'currency' => new CurrencyIndexResource($this->currency),
            // 'users' => new UserIndexResource($this->users),
            // 'records' => RecordIndexResource::collection($this->records),
        ];
        return $data;
    }
}
