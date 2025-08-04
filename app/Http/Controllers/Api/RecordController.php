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

        return response()->json($records);
    }

    public function store(RecordRequest $request)
    {
        $this->service->storeRecord($request);
        return response()->json('record created successfully', 201);
    }

    public function update(RecordRequest $request, Record $record)
    {
        $updateRecord = $this->service->updateRecord($request, $record);
        return response()->json('Record updated successfully', 204);
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
