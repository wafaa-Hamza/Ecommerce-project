<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shipping\IndexShippingRequest;
use App\Models\Shipping;
use App\Http\Requests\StoreShippingRequest;
use App\Http\Requests\UpdateShippingRequest;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexShippingRequest $request)
    {
        $data = $request->validated();
        $query = Shipping::query();

        $query->when(isset($data['type']), function ($query) use ($data) {
            $query->where('type', $data['type']);
        });

        $shippings = $query->paginate($data['per_page'] ?? 15);

        return $this->respondOk($shippings, 'Shippings fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShippingRequest $request)
    {
        $data = $request->validated();
        
        if($data['type'] == 'City'){
            if(Shipping::where([['type', 'City'],['value', $data['value']]])->exists()){
                return $this->respondError('Shipping for this City already exists');
            }
        }else if($data['type'] == 'Product'){
            if(Shipping::where([['type', 'Product'],['value', $data['value']]])->exists()){
                return $this->respondError('Shipping for this Product already exists');
            }
        } else if($data['type'] == 'Category'){
            if(Shipping::where([['type', 'Category'],['value', $data['value']]])->exists()){
                return $this->respondError('Shipping for this Category already exists');
            }
        } else if($data['type'] == 'Equal'){
            if(Shipping::where([['type', 'Equal'],['value', $data['value']]])->exists()){
                return $this->respondError('Shipping for this Equal already exists');
            }
        }

        $shipping = Shipping::create($data);

        return $this->respondCreated($shipping, 'Shipping created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipping $shipping)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShippingRequest $request, Shipping $shipping)
    {
        $data = $request->validated();

        if($data['type'] == 'City'){
            if(Shipping::where([['type', 'City'],['value', $data['value']]])->where('id', '!=', $shipping->id)->exists()){
                return $this->respondError('Shipping for this City already exists');
            }
        }else if($data['type'] == 'Product'){
            if(Shipping::where([['type', 'Product'],['value', $data['value']]])->where('id', '!=', $shipping->id)->exists()){
                return $this->respondError('Shipping for this Product already exists');
            }
        } else if($data['type'] == 'Category'){
            if(Shipping::where([['type', 'Category'],['value', $data['value']]])->where('id', '!=', $shipping->id)->exists()){
                return $this->respondError('Shipping for this Category already exists');
            }
        } else if($data['type'] == 'Equal'){
            if(Shipping::where([['type', 'Equal'],['value', $data['value']]])->where('id', '!=', $shipping->id)->exists()){
                return $this->respondError('Shipping for this Equal already exists');
            }
        }
        
        $shipping->update($data);
        return $this->respondOk($shipping, 'Shipping updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipping $shipping)
    {
        $shipping->delete();
        return $this->respondNoContent();
    }
}
