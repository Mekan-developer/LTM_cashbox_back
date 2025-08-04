<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Cashbox\AnalyticResource;
use App\Models\Cashbox;
use App\Models\ExchangeRate;
use App\Models\Record;
use App\Services\AnalyticService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnalyticsController extends Controller
{
    public function __construct(protected AnalyticService $service) {}

    public function summary(Request $request)
    {

        $data = $this->service->getAnalyticSummary($request);

        return response()->json($data);
    }
}
