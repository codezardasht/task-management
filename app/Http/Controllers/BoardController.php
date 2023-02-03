<?php

namespace App\Http\Controllers;

use App\Http\Resources\BoardCollection;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use App\Http\Requests\StoreBoardRequest;
use App\Http\Requests\UpdateBoardRequest;
use Carbon\Carbon;

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
     * @param  \App\Http\Requests\StoreBoardRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBoardRequest $request)
    {
        $board = new Board;
        $board->name = $request->name;
        $board->created_by = \auth()->id();
        $board->created_at = Carbon::now();
        return $board->save()
            ? response()->json([
                'status' => true,
                "message" => "Create Board Successfully",
            ])
            : response()->json([
                'status' => false,
                "message" => "Please Try Again !",
            ], 403);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Board  $board
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
     * @param  \App\Http\Requests\UpdateBoardRequest  $request
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