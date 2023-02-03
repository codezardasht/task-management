<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
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
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role;
        $user->password = Hash::make($request->password);
        $user->created_by = \auth()->id();
        $user->created_at = Carbon::now();

        if ($user->save()) {
            $user->assignRole($request->role);
            return response()->json([
                'status' => true,
                "message" => "User Created Successfully",
            ]);
        } else {
            return response()->json([
                'status' => false,
                "message" => "Please Try Again !",
            ], 403);
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
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
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
