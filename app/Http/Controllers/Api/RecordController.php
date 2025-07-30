<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RecordRequest;
use App\Http\Resources\Record\IndexResource;
use App\Models\Cashbox;
use App\Models\ExchangeRate;
use App\Models\Record;
use App\Repositories\RecordRepository;
use App\Services\RecordService;
use Illuminate\Support\Facades\Log as FacadesLog;

class RecordController extends Controller
{
    public function __construct(protected RecordService $service) {}

    public function index()
    {
        $records = $this->service->getRecords();

        return response()->json([
            'data' => $records,
            'message' => 'data recived successfully!'
        ]);
    }

    public function store(RecordRequest $request)
    {
        $storedRecord = $this->service->storeRecord($request);
        $storedRecord = new IndexResource($storedRecord);
        $storedRecordWithCashbox = $storedRecord->resolve();

        return response()->json($storedRecordWithCashbox);
    }

    public function show(Record $record)
    {
        return $record;
    }

    public function destroy(Record $record)
    {
        $record->delete();
        return response()->noContent();
    }
}
