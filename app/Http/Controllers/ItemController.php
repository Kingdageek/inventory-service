<?php

namespace App\Http\Controllers;

use App\Services\ItemService;
use App\Http\Requests\ItemRequest;

class ItemController extends Controller
{
    private $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->itemService->getAll();
        return $this->success_response($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemRequest $request)
    {
        $params = $request->validated();
        $item = $this->itemService->create($params);
        return $this->success_response($item, "Item created successfully", 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $item = $this->itemService->getById($id);
        return $this->success_response($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ItemRequest $request, int $id)
    {
        $params = $request->validated();
        $item = $this->itemService->update($params, $id);
        return $this->success_response($item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $deleted = $this->itemService->delete($id);
        return $this->success_response(
            $deleted,
            "Item successfully deleted",
            204
        );
    }
}
