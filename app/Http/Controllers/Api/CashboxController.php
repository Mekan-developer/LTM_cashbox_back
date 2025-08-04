<?php

namespace App\Http\Controllers\Api;

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
    public function store(CashboxRequest $request, CashboxService $service)
    {
        $cashbox = $service->createCashbox($request->validated());
        return new CashboxIndexResource($cashbox);
    }

    /**
     * Display the specified resource.
     */

    // Получить одну кассу
    public function show(Cashbox $cashbox)
    {
        return $cashbox->load('currency', 'users', 'records');
    }

    /**
     * Update the specified resource in storage.
     */
    // Обновить кассу
    public function update(Request $request, Cashbox $cashbox)
    {

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'currency_id' => 'sometimes|exists:currencies,id',
            'description' => 'nullable|string',
            'user_ids' => 'nullable|array'
        ]);
        $cashbox->update($validated);

        $validated['user_ids'] = Auth::id();
        if (isset($validated['user_ids'])) {
            $cashbox->users()->sync($validated['user_ids']);
        }

        return response()->json($cashbox->load('currency', 'users'));
    }

    /**
     * Remove the specified resource from storage.
     */
    // Удалить кассу
    public function destroy(Cashbox $cashbox)
    {
        Log::info('delllllelelelllleleetetete:');
        $cashbox->delete();
        return response()->json(['message' => 'Касса удалена']);
    }
}
