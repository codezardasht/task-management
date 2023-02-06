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
        $this->authorize('view_label');
        $search = request('search');
        $labels = Label::when($search != "" , function ($query) use ($search){
            $query->where('name' , 'LIKE' , '%'.$search.'%');
        })->latest()->get();
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
        $this->authorize('create_label');
        $label = new Label;
        $label->name = $request->name;
        $label->color = $request->color;
        $label->created_by = \auth()->id();
        $label->created_at = Carbon::now();
        return $label->save()
            ? store_message("Label")
            : try_again_message();
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
        $this->authorize('update_label');
        $label->name = $request->name;
        $label->color = $request->color;
        $label->created_by = \auth()->id();
        $label->created_at = Carbon::now();
        return $label->save()
            ? update_message("Label")
            : try_again_message();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Label $label)
    {
        $this->authorize('delete_label');
        return $label->delete()
            ? delete_message("Label")
            : try_again_message() ;
    }
}
