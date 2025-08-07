<?php

namespace App\Repositories;

use App\Http\Resources\Cashbox\IndexResource;
use App\Models\Cashbox;

class CashboxRepository
{
    public function getCashboxsPaginated($perPage = 15)
    {
        $cashboxes = Cashbox::with(['currency', 'users', 'records'])->paginate($perPage);
        return $cashboxes;
    }

    public function getCashboxById($id): Object
    {
        $cashbox = Cashbox::findOrFail($id);
        return $cashbox;
    }

    public function create(Object $dto): Cashbox
    {
        return Cashbox::create([
            'title' => $dto->title,
            'currency_id' => $dto->currency_id,
            'description' => $dto->description
        ]);
    }
    public function updateCashbox($cashbox, $dto)
    {
        $cashbox->update([
            'title' => $dto->title,
            'currency_id' => $dto->currency_id,
            'description' => $dto->description
        ]);
    }

    public function attachUsers(Cashbox $cashbox, int $userIds): void
    {
        $cashbox->users()->sync($userIds);
    }

    public function getCashboxByCurrencyRecord($cashbox)
    {
        return $cashbox->load('currency', 'users', 'records');
    }
}
