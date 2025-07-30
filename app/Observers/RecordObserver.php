<?php

namespace App\Observers;

use App\Models\Record;
use Illuminate\Support\Facades\Cache;

class RecordObserver
{
    /**
     * Handle the Record "created" event.
     */
    public function created(Record $record): void
    {
        // Cache::forget('records');
    }

    /**
     * Handle the Record "updated" event.
     */
    public function updated(Record $record): void
    {
        //
    }

    /**
     * Handle the Record "deleted" event.
     */
    public function deleted(Record $record): void
    {
        //
    }

    /**
     * Handle the Record "restored" event.
     */
    public function restored(Record $record): void
    {
        //
    }

    /**
     * Handle the Record "force deleted" event.
     */
    public function forceDeleted(Record $record): void
    {
        //
    }
}
