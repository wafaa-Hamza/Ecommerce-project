<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\DeleteCartRequest;
use App\Http\Requests\Cart\StoreCartRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\MediaResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
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
    public function store(StoreCartRequest $request)
    {
        
        $data = $request->validated();
        $user = $request->user;

        $product = Product::find($data['product_id']);

        if(!$product) {
            return $this->respondError('Product not found');
        }

        if($product->quantity < $data['quantity']) {
            return $this->respondError('Insufficient quantity, existing quantity is '.$product->quantity);
        }
        
        $user->cart()->syncWithoutDetaching([$data['product_id'] => ['quantity' => $data['quantity']]]);

        return $this->respondNoContent();

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $user = $request->user;
        // $result = DB::table('product_user')
        // ->join('products', 'product_user.product_id', '=', 'products.id')
        // ->where('product_user.user_id', $user->id)
        // ->select(
        //     'products.id',
        //     'products.name',
        //     'products.price',
        //     'products.priceAfter',
        //     'products.category_id',
        //     'products.quantity as productQuantity',
        //     'product_user.quantity as cartQuantity',
        //     DB::raw('(product_user.quantity * (products.price - products.priceAfter)) as total_discount'),
        //     DB::raw('(product_user.quantity * products.priceAfter) as total_price')
        // )
        // ->get();
    
        $result = $user->cart()->select(
            'products.id',
            'products.name',
            'products.price',
            'products.priceAfter',
            'products.category_id',
            'products.quantity as productQuantity',
            'product_user.quantity as cartQuantity',
            DB::raw('(product_user.quantity * (products.price - products.priceAfter)) as total_discount'),
            DB::raw('(product_user.quantity * products.priceAfter) as total_price')
        )->get();

        $totalPriceSum = round($result->sum('total_price'), 2);
        $totalDiscountSum = round($result->sum('total_discount'), 2);
        
        return $this->respondOk([
            'cartItems' => CartResource::collection($result),
            'total_price' => $totalPriceSum,
            'total_discount' => $totalDiscountSum
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteCartRequest $request)
    {

        $data = $request->validated();
        $user = $request->user;

        $item = DB::table('product_user')
        ->where('product_user.user_id', $user->id)
        ->where('product_user.product_id', $data['product_id'])
        ->first();

        if(!$item) {
            return $this->respondError('Item not found in cart');
        }
        
        $user->cart()->detach($data['product_id']);
        
        return $this->respondNoContent();
    }
}
