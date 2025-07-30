<?php

namespace App\Observers;

use App\Models\Cashbox;
use Illuminate\Support\Facades\Log;

class CashboxObserver
{
    /**
     * Handle the Cashbox "created" event.
     */
    public function created(Cashbox $cashbox): void
    {
        // Log::info('Cashbox created works');
    }

    /**
     * Handle the Cashbox "updated" event.
     */
    public function updated(Cashbox $cashbox): void
    {
        //
    }

    /**
     * Handle the Cashbox "deleted" event.
     */
    public function deleted(Cashbox $cashbox): void
    {
        //
    }

    /**
     * Handle the Cashbox "restored" event.
     */
    public function restored(Cashbox $cashbox): void
    {
        //
    }

    /**
     * Handle the Cashbox "force deleted" event.
     */
    public function forceDeleted(Cashbox $cashbox): void
    {
        //
    }
}
