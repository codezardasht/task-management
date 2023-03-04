<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Session;

class PostController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('view_post');
        return view('post.index');
    }

    public function data(Request $request)
    {
        $this->authorize('view_post');
        if ($request->ajax()) {
            $post = Post::all();

            return Datatables::of($post)
                ->editColumn('created_by', function ($data) {
                    if ($data->User) {
                        return $data->User->name;
                    } else {
                        return "";
                    }
                })->editColumn('created_at', function ($data) {
                    $createdAt = Carbon::parse($data->created_at);
                    return $createdAt->format('Y-m-d h:i:s');
                })->editColumn('checkbox', function ($data) {

                    if ($data->active == 1) {
                        $status = '' . __('translate.active') . '';
                        $status_check = 'checked';
                        $status_label = '';
                        $checkbox_color = 'form-check-input-success';
                    } else {
                        $status = '' . __('translate.suspend') . '';
                        $status_check = '';
                        $status_label = 'text-danger';
                        $checkbox_color = 'form-check-input-secondary';
                    }

                    $checkbox = '<label class="form-check mb-2">
            															<input type="checkbox" class="form-check-input ' . $checkbox_color . ' checkbox_change_status " ' . $status_check . ' data-route="' . route('post.status_change', [$data->id]) . '">
            															<span class="form-check-label ' . $status_label . '">' . $status . '</span>
            														</label>';

                    return $checkbox;


                })
                ->addColumn('action', function ($data) {
                    $button = '';


                    $button .= '<div>';

                    if (\Auth::user()->can('update_post')) {
                        $button .= '<a href="#" class="btn btn-sm btn-outline-indigo rounded-pill p-2" id="edit" data-id="' . $data->id . '" ><i class="fa fa-edit edit_icon_' . $data->id . '"></i> </a>';

                    }
                    if (\Auth::user()->can('delete_post')) {
                        $button .= '<a href="#"  id="delete" data-id="' . $data->id . '" data-route="' . route("post.destroy", [$data->id]) . '" class="btn btn-sm btn-outline-danger rounded-pill p-2 ms-1"><i class="fa fa-trash"></i> </a>';
                    }

                    $button .= '</div>';


                    return $button;
                })
                ->addIndexColumn()
                ->rawColumns(['action', 'active', 'checkbox'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->authorize('add_post');


        $post = new  Post();
        $post->title = $request->title;
        $post->desc = $request->desc;

        $post->created_by = Auth::id();
        $post->created_at = Carbon::now();

        if ($post->save()) {
            return response()->json([
                'status' => 'success',
                "message" => "Post Create Successfully"
            ]);
        } else {
            return response()->json([
                'status' => 'fail',
                "message" => "Please Try Again !"
            ], 500);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Post $post)
    {
        $this->authorize('update_post');
        $html = view_template_part('post.update', compact('post'));
        return response()->json([
            'status' => true,
            'html' => $html,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update_post');


        $post->title = $request->title;
        $post->desc = $request->desc;

        $post->updated_by = Auth::id();
        $post->updated_at = Carbon::now();
        if ($post->save()) {

            return response()->json([
                'status' => 'success',
                "message" => "post Updated Successfully"
            ]);
        } else {
            return response()->json([
                'status' => 'fail',
                "message" => "Please Try Again !"
            ], 500);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete_post');
        $post->delete();

        return response()->json([
            'status' => true,
            'message' => 'Record deleted successfully!'
        ]);

    }

    public function status_change(Post $post)
    {
        $this->authorize('update_post');
        if ($post->active == 1) {
            $post->active = 0;
        } else {
            $post->active = 1;
        }
        $post->save();
        return response()->json([
            'status' => true,
            'message' => 'Status Change successfully!'
        ]);
    }
}
