<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\IndexUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexUserRequest $request)
    {
        $data = $request->validated();
        $query = User::query()->withoutRole('super_admin');
        
        $query->when(isset($data['role']) , function($query) use($data){
            if ($data['role'] == 'All_Admins') {
                $query->role(['over_view' , 'category' , 'product' , 'order' , 'stock' , 'message' , 'shipping' , 'admin' , 'setting']);
            }else {  
                $query->role($data['role']);
            }
        });
        $query->when(isset($data['query']) , function($query) use($data){
            $query->where(fn ($query) => $query->where('name' , 'like' , '%' . $data['query'] . '%')->orWhere('email' , 'like' , '%' . $data['query'] . '%'));
        });

        $users = $query->paginate($data['per_page'] ?? 15);

        return $this->respondOk(UserResource::collection($users), 'Users fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $user = User::create($data);
        $user->assignRole($data['roles']);
        return $this->respondCreated(new UserResource($user) , 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function update(UpdateUserRequest $request , User $user)
    {
        $data = $request->validated();

        if ($user->hasRole('super_admin')){
            return $this->respondError('You can not update super admin');
        }

        $user->update($data);

        if(isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        if ($request->hasFile('image')) {
            $user->clearMediaCollection("main");
            $user->addMediaFromRequest('image')->toMediaCollection("main");
        }

        return $this->respondOk(UserResource::make($user));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update_profile(UpdateUserRequest $request)
    {
        $data = $request->validated();
        $user = $request->user;

        $user->update($data);

        if ($request->hasFile('image')) {
            $user->clearMediaCollection("main");
            $user->addMediaFromRequest('image')->toMediaCollection("main");
        }

        return $this->respondOk(UserResource::make($user));

    }

    public function my_profile(Request $request)
    {
        $user = $request->user;
        return $this->respondOk(UserResource::make($user));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->hasRole('super_admin')){
            return $this->respondError('You can not delete super admin');
        }

        $user->delete();
        return $this->respondOk('User deleted successfully');
    }
}
