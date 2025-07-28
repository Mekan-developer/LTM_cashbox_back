<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CashboxRequest;
use App\Models\Cashbox;
use Illuminate\Http\Request;

class CashboxController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // Получить все кассы
    public function index()
    {
        return Cashbox::with('currency', 'users')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    // Создать новую кассу
    public function store(CashboxRequest $request)
    {
        $validated = $request->validated();
        $cashbox = Cashbox::create($validated);

        if (!empty($request['user_ids'])) {
            $cashbox->users()->sync($request['user_ids']);
        }

        return response()->json($cashbox->load('currency', 'users'), 201);
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
        $cashbox->delete();
        return response()->json(['message' => 'Касса удалена']);
    }
}
