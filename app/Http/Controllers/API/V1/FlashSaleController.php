<?php

namespace App\Http\Controllers\API\V1;

use App\Models\FlashSale;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFlashSaleRequest;
use App\Http\Requests\UpdateFlashSaleRequest;
use App\Http\Resources\FlashSaleResource;

class FlashSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flashSale = FlashSale::with('items')->first();
        if (!$flashSale) {
            return $this->respondError("Flash Sale not found");
        }
        return $this->respondOk(FlashSaleResource::make($flashSale), 'Flash Sale fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFlashSaleRequest $request)
    {
        $data = $request->validated();

        $flashSale = FlashSale::first();

        if ($flashSale) {
            return $this->respondError("Can't create more than one flash sale");
        }

        $flashSale = FlashSale::create($data);
        $items = $data['items'];

        foreach ($items as $item) {
            $currentItem = $flashSale->items()->create($item);
            $currentItem->addMedia($item['image'])->toMediaCollection("main");
        }

        return $this->respondOk(FlashSaleResource::make($flashSale), 'Flash Sale created successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(FlashSale $flashSale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFlashSaleRequest $request, FlashSale $flashSale)
    {
        $data = $request->validated();
        $flashSale->update($data);
        return $this->respondOk(FlashSaleResource::make($flashSale), 'Flash Sale updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FlashSale $flashSale)
    {
        $flashSale->delete();
        return $this->respondNoContent();
    }
}
