<?php

namespace App\Http\Controllers;

use App\Http\Requests\Board\StoreBoardRequest;
use App\Http\Requests\Board\UpdateBoardRequest;
use App\Http\Resources\Board\BoardCollection;
use App\Http\Resources\Board\BoardResource;
use App\Models\Board;
use App\Models\StatusBoard;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return BoardCollection
     */
    public function index()
    {
        $boards = Board::all();
        return new BoardCollection($boards);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Board\StoreBoardRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBoardRequest $request)
    {

        $result = DB::transaction(function () use ($request) {
            $board = new Board;
            $board->name = $request->name;
            $board->created_by = \auth()->id();
            $board->created_at = Carbon::now();
            $board->save();

            $this->store_board_status($board->id);
            return $board;
        });


        return ($result)
            ? store_message('Board')
            : try_again_message();
    }


    public function store_board_status($board_id)
    {
        $status = ['To-do' => ['is_assign' => 0, 'role' => [
            'Developer',
            'Product Owner'
        ]], 'In-Progress' => ['is_assign' => 1, 'role' => [
            'developers',
            'Product Owner'
        ]], 'Dev-Review' => ['is_assign' => 1, 'role' => [
            'developers',
            'Product Owner'
        ]], 'Testing' => ['is_assign' => 1, 'role' => [
            'testers',
            'Product Owner'
        ]], 'Done' => ['is_assign' => 1, 'role' => [
            'Product Owner'
        ]], 'Close' => ['is_assign' => 1, 'role' => [
            'Product Owner'
        ]],];
        $date_now = Carbon::now();

        return DB::transaction(function () use ($board_id, $status, $date_now) {
            foreach ($status as $key => $status) {

                $is_assign  = $status["is_assign"];
                $roles  = $status["role"];
                $role_ids = $this->get_role_id($roles);

                $newStatusBoard = new StatusBoard;
                $newStatusBoard->board_id = $board_id;
                $newStatusBoard->name = $key;
                $newStatusBoard->is_assign = "$is_assign";
                $newStatusBoard->role_ids = $role_ids;
                $newStatusBoard->created_by = auth()->id();
                $newStatusBoard->created_at = $date_now;
                $newStatusBoard->save();
            }
            return $status;
        });
    }

    public function get_role_id($roles)
    {
        return DB::transaction(function () use ($roles) {
            if(count($roles) > 0)
            {
                $roleIds = [];
                foreach ($roles as $roleName) {
                    $role = Role::where('name', $roleName)->first();
                    if ($role) {
                        $roleIds[] = $role->id;
                    }
                }
               return implode(',' , $roleIds);
            }

        });
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Board $board
     * @return BoardResource
     */
    public function show(Board $board)
    {
        return new BoardResource($board);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Board\UpdateBoardRequest  $request
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBoardRequest $request, Board $board)
    {
        $board->name = $request->name;
        $board->updated_by = \auth()->id();
        $board->updated_at = Carbon::now();
        return $board->save()
            ? response()->json([
                'status' => true,
                "message" => "Update Board Successfully",
            ])
            : response()->json([
                'status' => false,
                "message" => "Please Try Again !",
            ], 403);
    }


    public function status_board(Board $board)
    {
        $boardStatus = $board->with('status_board')->first();
        return new BoardResource($boardStatus);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Board $board)
    {
        return $board->delete()
            ? response()->json([
                'status' => true,
                "message" => "Board Deleted Successfully",
            ])
            : response()->json([
                'status' => false,
                "message" => "Please Try Again !",
            ], 403);
    }
}
