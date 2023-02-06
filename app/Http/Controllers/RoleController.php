<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\Role\RoleCollection;
use App\Http\Resources\Role\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return RoleCollection
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();

        return new RoleCollection($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRoleRequest $request)
    {

        $result = DB::transaction(function () use ($request){
            $role = Role::create(['name' => $request->name]);
            $role->syncPermissions($request->permissions);

            return $role;
        });

        return ($result)
            ? store_message('Role')
            : try_again_message();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return RoleResource
     */
    public function show(Role $role)
    {
        return new RoleResource($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $result = DB::transaction(function () use ($request , $role){
            $role->name = $request->name;
            $role->syncPermissions($request->permissions);
            $role->save();
            return $role;
        });

        return ($result)
            ? update_message('Role')
            : try_again_message();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        $result = DB::transaction(function () use ( $role){
            // Detach the role's associated permissions
            $role->permissions()->detach();
            // Delete the role
            $role->delete();
            return $role;
        });
        return($result)
            ? delete_message("Role")
            : try_again_message() ;
    }
}
