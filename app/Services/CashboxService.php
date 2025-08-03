<?php

namespace App\Services;

use App\Models\Cashbox;
use App\Repositories\CashboxRepository;
use Illuminate\Support\Facades\Auth;

class CashboxService
{
    public function __construct(protected CashboxRepository $repository) {}

    public function createCashbox(array $data): Cashbox
    {
        $userIds = Auth::id();
        $cashbox = $this->repository->create($data);
        if (!empty($userIds)) {
            $this->repository->attachUsers($cashbox, $userIds);
        }

        return $cashbox->load('currency', 'users');
    }
}
