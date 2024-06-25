<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\FlashSaleItem;
use App\Http\Requests\StoreFlashSaleItemRequest;
use App\Http\Requests\UpdateFlashSaleItemRequest;
use App\Http\Resources\FlashSaleItemResource;

class FlashSaleItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFlashSaleItemRequest $request)
    {
        $data = $request->validated();
        $flashSale = FlashSaleItem::where('product_id' , $data['product_id'])->first();

        if($flashSale){
            return $this->respondError("Flash Sale item already exists");
        }
        
        $flashSale = FlashSaleItem::create($data);
        $flashSale->addMediaFromRequest('image')->toMediaCollection("main");

        return $this->respondCreated(FlashSaleItemResource::make($flashSale)); 
    }

    /**
     * Display the specified resource.
     */
    public function show(FlashSaleItem $flashSaleItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFlashSaleItemRequest $request, FlashSaleItem $flashSaleItem)
    {
        $flashSaleItem->update($request->validated());

        if ($request->hasFile('image')) {
            $flashSaleItem->clearMediaCollection("main");
            $flashSaleItem->addMediaFromRequest('image')->toMediaCollection("main");
        }

        return $this->respondOk(FlashSaleItemResource::make($flashSaleItem), 'Flash Sale item updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FlashSaleItem $flashSaleItem)
    {
        $flashSaleItem->delete();
        return $this->respondNoContent();
    }
}
