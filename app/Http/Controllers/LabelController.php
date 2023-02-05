<?php

namespace App\Http\Controllers;

use App\Http\Requests\Label\StoreLabelRequest;
use App\Http\Requests\Label\UpdateLabelRequest;
use App\Http\Resources\Label\LabelCollection;
use App\Http\Resources\Label\LabelResource;
use App\Models\Label;
use Carbon\Carbon;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return LabelCollection
     */
    public function index()
    {
        $labels = Label::latest()->get();
        return new LabelCollection($labels);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Label\StoreLabelRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreLabelRequest $request)
    {
        $label = new Label;
        $label->name = $request->name;
        $label->color = $request->color;
        $label->created_by = \auth()->id();
        $label->created_at = Carbon::now();
        return $label->save()
            ? response()->json([
                'status' => true,
                "message" => "Create Lable Successfully",
            ],201)
            : response()->json([
                'status' => false,
                "message" => "Please Try Again !",
            ], 403);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return LabelResource
     */
    public function show(Label $label)
    {
        return new LabelResource($label);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Label\UpdateLabelRequest  $request
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateLabelRequest $request, Label $label)
    {
        $label->name = $request->name;
        $label->color = $request->color;
        $label->created_by = \auth()->id();
        $label->created_at = Carbon::now();
        return $label->save()
            ? response()->json([
                'status' => true,
                "message" => "Update Lable Successfully",
            ],201)
            : response()->json([
                'status' => false,
                "message" => "Please Try Again !",
            ], 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Label $label)
    {
        return $label->delete()
            ? response()->json([
                'status' => true,
                "message" => "Label Deleted Successfully",
            ])
            : response()->json([
                'status' => false,
                "message" => "Please Try Again !",
            ], 403);
    }
}
