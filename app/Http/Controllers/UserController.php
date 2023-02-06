<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return UserCollection
     */
    public function index()
    {
        $this->authorize('view_user');
        $users = User::all();

        return new UserCollection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserStoreRequest $request)
    {
        $this->authorize('create_user');
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role;
        $user->password = Hash::make($request->password);
        $user->created_by = \auth()->id();
        $user->created_at = Carbon::now();

        if ($user->save()) {
            $user->assignRole($request->role);
            return store_message('User');
        } else {
            return try_again_message();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return UserResource
     */
    public function show(User $user)
    {
        $this->authorize('update_user');
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $this->authorize('update_user');

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role;
        ($request->password != "") ?  $user->password = Hash::make($request->password) : "";
        $user->updated_by = \auth()->id();
        $user->updated_at = Carbon::now();



        if ($user->save()) {
            $user->syncRoles($request->role);
            return update_message('User');
        } else {
            return try_again_message();
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $this->authorize('delete_user');
        return $user->delete()
            ? response()->json([
                'status' => true,
                "message" => "User Deleted Successfully",
            ])
            : response()->json([
                'status' => false,
                "message" => "Please Try Again !",
            ], 403);
    }
}
