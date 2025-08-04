<?php

namespace App\Http\Controllers\Api;

use App\DTOs\CashboxDTO;
use App\Http\Controllers\Controller;
use App\Repositories\CashboxRepository;
use App\Http\Requests\Api\CashboxRequest;
use App\Http\Resources\Cashbox\IndexResource as CashboxIndexResource;
use App\Models\Cashbox;
use App\Services\CashboxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CashboxController extends Controller
{

    public function __construct(protected CashboxService $service) {}
    /**
     * Display a listing of the resource.
     */

    // Получить все кассы 
    public function index(CashboxRepository $cashboxRepository)
    {
        $data = CashboxIndexResource::collection($cashboxRepository->getCashboxs())->resolve();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    // Создать новую кассу
    public function store(CashboxRequest $request)
    {
        $dto = new CashboxDTO($request->validated());

        $this->service->createCashbox($dto);

        return response()->json([
            'message' => 'Пользователь created успешно.'
        ], 200);
    }

    /**
     * Display the specified resource.
     */

    // Получить одну кассу
    public function show(Cashbox $cashbox)
    {
        $data = $this->service->getCashbox($cashbox);
        return response()->json($data, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    // Обновить кассу
    public function update(CashboxRequest $request, Cashbox $cashbox)
    {
        $dto = new CashboxDTO($request->validated());
        $this->service->updateCashbox($dto, $cashbox);

        return response()->json([
            'message' => 'user updated succeffully!.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    // Удалить кассу
    public function destroy(Cashbox $cashbox)
    {
        $cashbox->delete();
        return response()->json([
            'message' => 'Пользователь удалён успешно.'
        ], 200);
    }
}
