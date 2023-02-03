<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\ListBoard;
use App\Http\Requests\StoreListBoardRequest;
use App\Http\Requests\UpdateListBoardRequest;
use Carbon\Carbon;

class ListBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreListBoardRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreListBoardRequest $request)
    {
        $listBoard = new ListBoard;
        $listBoard->name = $request->name;
        $listBoard->board_id = $request->board_id;
        $listBoard->created_by = \auth()->id();
        $listBoard->created_at = Carbon::now();
        return $listBoard->save()
            ? response()->json([
                'status' => true,
                "message" => "Create List Board Successfully",
            ])
            : response()->json([
                'status' => false,
                "message" => "Please Try Again !",
            ], 403);
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ListBoard  $listBoard
     * @return \Illuminate\Http\Response
     */
    public function show(ListBoard $listBoard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateListBoardRequest  $request
     * @param  \App\Models\ListBoard  $listBoard
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateListBoardRequest $request, ListBoard $listBoard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ListBoard  $listBoard
     * @return \Illuminate\Http\Response
     */
    public function destroy(ListBoard $listBoard)
    {
        //
    }
}
