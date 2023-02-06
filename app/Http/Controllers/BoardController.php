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
        $status = ['To-do', 'In-Progress', 'Dev-Review', 'Testing', 'Done', 'Close'];
        $date_now = Carbon::now();

        return DB::transaction(function () use ($board_id, $status , $date_now) {
            foreach ($status as $status) {
                $newStatusBoard = new StatusBoard;
                $newStatusBoard->board_id = $board_id;
                $newStatusBoard->name = $status;
                $newStatusBoard->created_by = auth()->id();
                $newStatusBoard->created_at = $date_now;
                $newStatusBoard->save();
            }
            return $status;
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


    public function list_board(Board $board)
    {
        $boardList = $board->with('lists')->first();
        return new BoardResource($boardList);
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
