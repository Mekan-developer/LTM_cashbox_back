<?php

namespace App\Repositories;

use App\Http\Resources\Cashbox\IndexResource;
use App\Models\Cashbox;

class CashboxRepository
{
    public function getCashboxs()
    {
        $cashboxes = Cashbox::with(['currency', 'users', 'records'])->get();
        return $cashboxes;
    }

    public function getCashboxById($id): object
    {
        $cashbox = Cashbox::findOrFail($id);
        return $cashbox;
    }

    public function create(array $data): Cashbox
    {
        return Cashbox::create($data);
    }

    public function attachUsers(Cashbox $cashbox, int $userIds): void
    {
        $cashbox->users()->sync($userIds);
    }
}
