<?php

namespace App\Services;

use App\DTOs\CashboxDTO;
use App\Models\Cashbox;
use App\Repositories\CashboxRepository;
use Illuminate\Support\Facades\Auth;

class CashboxService
{
    public function __construct(protected CashboxRepository $repository) {}

    public function createCashbox(Object $dto): Void
    {
        $cashbox = $this->repository->create($dto);
        $userIds = Auth::id();
        if (!empty($userIds)) {
            $this->repository->attachUsers($cashbox, $userIds);
        }
    }

    public function getCashbox($cashbox)
    {
        $data = $this->repository->getCashboxByCurrencyRecord($cashbox);
        return $data;
    }

    public function updateCashbox($dto, $cashbox)
    {
        $this->repository->updateCashbox($cashbox, $dto);
        $user_ids = Auth::id();
        if (isset($user_ids)) {
            $cashbox->users()->sync($user_ids);
        }
    }
}
