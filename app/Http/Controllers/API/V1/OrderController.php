<?php

namespace App\Http\Controllers\API\V1;

use App\Enums\OrderStatusType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\IndexOrderRequest;
use App\Models\Order;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Models\Setting;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexOrderRequest $request)
    {

        $data = $request->validated();
        $per_page = $data['per_page'] ?? 15;

        $query = Order::query()->latest();

        $query->when(isset($data['query']) , function($query) use($data){
            $query->where('name' , 'like' , '%' . $data['query'] . '%');
        });

        $query->when(isset($data['status']) , function($query) use($data){
            $query->where('status' , OrderStatusType::fromKey($data['status'])->value);
        });

        $query->when(isset($data['sort_by']) , function($query) use($data){
            if($data['asc']){
                $query->orderBy($data['sort_by']);
            } else{
                $query->orderByDesc($data['sort_by']);
            }
        });
        
        $query->leftJoin('order_product', 'orders.id', '=', 'order_product.order_id')
        ->leftJoin('products', 'order_product.product_id', '=', 'products.id')
        ->select('orders.id', 'orders.user_id', 'orders.status', 'orders.total', 'orders.email', 'orders.phone', 'orders.address', 'orders.name', 'orders.city', 'orders.postal_code', 'orders.created_at', 'orders.updated_at')
        ->selectRaw('SUM(order_product.quantity * (products.price - products.priceAfter)) as total_discount')
        ->groupBy('orders.id', 'orders.user_id', 'orders.status', 'orders.total', 'orders.email', 'orders.phone', 'orders.address', 'orders.name', 'orders.city', 'orders.postal_code', 'orders.created_at', 'orders.updated_at')
        ->with(['products' => function ($query) {
            $query->selectRaw(
                "products.id,
                 products.quantity,
                 products.priceAfter,
                 products.name,
                 products.sku,
                 order_product.quantity * (products.price - products.priceAfter) as total_discount"
            );
        }]);
        $orders = $query->paginate($per_page);

        return $this->respondOk($orders, 'Orders fetched successfully');

    }


    /**
     * Display a listing of the resource.
     */
    public function my_order(IndexOrderRequest $request)
    {

        $data = $request->validated();
        $user = $request->user;
        $per_page = $data['per_page'] ?? 15;

        $query = $user->orders()->latest();

        $query->when(isset($data['query']) , function($query) use($data){
            $query->where('name' , 'like' , '%' . $data['query'] . '%');
        });

        $query->when(isset($data['status']) , function($query) use($data){
            $query->where('status' , OrderStatusType::fromKey($data['status'])->value);
        });

        $query->when(isset($data['sort_by']) , function($query) use($data){
            if($data['asc']){
                $query->orderBy($data['sort_by']);
            } else{
                $query->orderByDesc($data['sort_by']);
            }
        });

        $query->leftJoin('order_product', 'orders.id', '=', 'order_product.order_id')
        ->leftJoin('products', 'order_product.product_id', '=', 'products.id')
        ->select('orders.id', 'orders.user_id', 'orders.status', 'orders.total', 'orders.email', 'orders.phone', 'orders.address', 'orders.name', 'orders.city', 'orders.postal_code', 'orders.created_at', 'orders.updated_at')
        ->selectRaw('SUM(order_product.quantity * (products.price - products.priceAfter)) as total_discount')
        ->groupBy('orders.id', 'orders.user_id', 'orders.status', 'orders.total', 'orders.email', 'orders.phone', 'orders.address', 'orders.name', 'orders.city', 'orders.postal_code', 'orders.created_at', 'orders.updated_at')
        ->with(['products' => function ($query) {
            $query->selectRaw(
                "products.id,
                 products.quantity,
                 products.priceAfter,
                 products.name,
                 products.sku,
                 order_product.quantity * (products.price - products.priceAfter) as total_discount"
            );
        }]);
        
        $orders = $query->paginate($per_page);

        return $this->respondOk($orders, 'Orders fetched successfully');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $user = $request->user;

        $data = $request->validated();

        DB::beginTransaction();

        $result = DB::table('product_user')
        ->join('products', 'product_user.product_id', '=', 'products.id')
        ->where('product_user.user_id', $user->id)
        ->select(
            'products.id',
            'products.name',
            'products.price',
            'products.priceAfter',
            'products.category_id',
            'products.quantity as productQuantity',
            'product_user.quantity as cartQuantity',
            DB::raw('(product_user.quantity * (products.price - products.priceAfter)) as total_discount'),
            DB::raw('(product_user.quantity * products.priceAfter) as total_price')
        )
        ->get();

        if(count($result) == 0) {
            return $this->respondError("Cart is empty");
        }

        $totalPriceSum = round($result->sum('total_price'), 2);

        $priceOfCity = Shipping::where('type', 'City')->where('value', $data['city'])->first()->price;

        $perOrder = Shipping::where('type', 'Equal')->where('value', '<' ,$totalPriceSum)->orderBy('value', 'desc')->first();
        if ($perOrder) {
            $pricePerOrder = $perOrder->price;
        }

        $data['user_id'] = $user->id;
        $data['total'] = $totalPriceSum;

        $order = Order::create($data);


        if(count($result) == 1) {
            foreach($result as $row) {

                $perProduct = Shipping::where('type', 'Product')->where('value', $row->id)->first();
                if ($perProduct) {
                    $pricePerProduct = $perProduct->price;
                }

                $perCategory = Shipping::where('type', 'Category')->where('value', $row->category_id)->first();
                if ($perCategory) {
                    $pricePerCategory = $perCategory->price;
                }
                
                if($row->cartQuantity > $row->productQuantity) {
                    DB::rollBack();
                    return $this->respondError("Insufficient quantity, existing quantity is ".$row->productQuantity);
                }
    
                DB::table('products')->where('id', $row->id)->update(['quantity' => $row->productQuantity - $row->cartQuantity]);
    
                $order->products()->attach($row->id , [
                    'quantity' => $row->cartQuantity,
                    'price' => $row->total_price
                ]);
        
                $shipping = min($pricePerOrder ?? PHP_INT_MAX, $pricePerProduct ?? PHP_INT_MAX , $pricePerCategory ?? PHP_INT_MAX , $priceOfCity);

                $taxPercentage = 0;
                $taxNumber = 0;
                $setting = Setting::first();

                if ($setting) {
                    $taxPercentage = $setting->tax;
                }

                $order->update([
                    'shipping' => $shipping,
                    'tax' => $taxPercentage
                ]);

                $taxNumber = $taxPercentage / 100 * $totalPriceSum;

                $user->cart()->detach();

                DB::commit();

                return $this->respondOk(['price' => $totalPriceSum , 'shipping' => $shipping , 'taxPercentage' => $taxPercentage , 'taxNumber' => $taxNumber , 'total' => $totalPriceSum + $shipping + $taxNumber] , "Checkout successful");
    
            }
        } else {

            foreach($result as $row) {

                if($row->cartQuantity > $row->productQuantity) {
                    DB::rollBack();
                    return $this->respondError("Insufficient quantity, existing quantity is ".$row->productQuantity);
                }
    
                DB::table('products')->where('id', $row->id)->update(['quantity' => $row->productQuantity - $row->cartQuantity]);
    
                $order->products()->attach($row->id , [
                    'quantity' => $row->cartQuantity,
                    'price' => $row->total_price
                ]);
    
            }
    
            $user->cart()->detach();
                    
            DB::commit();
    
            $shipping = min($pricePerOrder ?? PHP_INT_MAX , $priceOfCity);
    
            $taxPercentage = 0;
            $taxNumber = 0;
            $setting = Setting::first();

            if ($setting) {
                $taxPercentage = $setting->tax;
            }

            $order->update([
                'shipping' => $shipping,
                'tax' => $taxPercentage
            ]);

            $taxNumber = $taxPercentage / 100 * $totalPriceSum;

            $user->cart()->detach();

            DB::commit();

            return $this->respondOk(['price' => $totalPriceSum , 'shipping' => $shipping , 'taxPercentage' => $taxPercentage , 'taxNumber' => $taxNumber , 'total' => $totalPriceSum + $shipping + $taxNumber] , "Checkout successful");

        }
        

    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order , Request $request)
    {
        $user = $request->user;

        if ($user->id != $order->user_id && !$user->hasRole('order') && !$user->hasRole('super_admin')){
            return $this->respondError("Unauthorized");
        }

        return $this->respondOk($order->load('products') , 'Order fetched successfully');
    }

   /**
     * Update the specified resource in storage.
     */
    public function update(Order $order , UpdateOrderRequest $request)
    {
        $data = $request->validated();

        // if($order->status == "Completed" || $order->status == "Canceled"){
        //     return $this->respondError("Cannot change status of an order that is Completed or Canceled " . "current status : " . $order->status);
        // }

        // if(($data['status'] == "Confirmed" || $data['status'] == "Rejected") && $order->status != "Pending"){ 
        //     return $this->respondError("Cannot Confirme or Reject order that is not pending ". "current status : " . $order->status);
        // }

        // if($data['status'] == "Completed" && $order->status != "Confirmed"){ 
        //     return $this->respondError("Cannot Complete an order that is not confirmed ". "current status : " . $order->status);
        // }

        $order->update([
            'status' => $data['status'],
        ]);

        return $this->respondNoContent();
        
    }

    public function destroy(Order $order , Request $request)
    {
        $user = $request->user;
        
        if ($user->id != $order->user_id){
            return $this->respondError("Unauthorized");
        }        

        if($order->status != "Pending"){
            return $this->respondError("Cannot Cancel an order that is not pending ". "current status : " . $order->status);
        }
        
        $order->update([
            'status' => 'Cancelled',
        ]);

        return $this->respondNoContent();
        
    }

}
