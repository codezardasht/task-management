<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\PostEconomy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Session;

class PostEconomyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('view_post-economy');
        return view('post-economy.index');
    }

     public function data(Request $request)
        {
           $this->authorize('view_post-economy');
            if ($request->ajax()) {
                        $post-economy = PostEconomy::all();

                        return Datatables::of($post-economy)
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
                                    $status = ''.__('translate.active').'';
                                    $status_check = 'checked';
                                    $status_label = '';
                                    $checkbox_color = 'form-check-input-success';
                                } else {
                                    $status = ''.__('translate.suspend').'';
                                    $status_check = '';
                                    $status_label = 'text-danger';
                                    $checkbox_color = 'form-check-input-secondary';
                                }

                                $checkbox = '<label class="form-check mb-2">
            															<input type="checkbox" class="form-check-input '.$checkbox_color.' checkbox_change_status " '.$status_check.' data-route="'.route('post-economy.status_change',[$data->id]).'">
            															<span class="form-check-label '.$status_label.'">'.$status.'</span>
            														</label>';

                                return $checkbox;


                            })
                            ->addColumn('action', function ($data) {
                                $button = '';



                                $button .= '<div>';

                                if (\Auth::user()->can('update_post-economy')) {
                                    $button .= '<a href="#" class="btn btn-sm btn-outline-indigo rounded-pill p-2" id="edit" data-id="' . $data->id . '" ><i class="fa fa-edit edit_icon_' . $data->id . '"></i> </a>';

                                }
                                if (\Auth::user()->can('delete_post-economy')) {
                                    $button .= '<a href="#"  id="delete" data-id="' . $data->id . '" data-route="' . route("post-economy.destroy", [$data->id]) . '" class="btn btn-sm btn-outline-danger rounded-pill p-2 ms-1"><i class="fa fa-trash"></i> </a>';
                                }

                                $button .= '</div>';


                                return $button;
                            })
                            ->addIndexColumn()
                            ->rawColumns(['action','active','checkbox'])
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
         $this->authorize('add_post-economy');
        

        $post-economy = new  PostEconomy();
        $posteconomy->title = $request->title;
$posteconomy->desc = $request->desc;

        $post-economy->created_by = Auth::id();
        $post-economy->created_at = Carbon::now();
         
           if ($post-economy->save()) {
                    return response()->json([
                        'status' => 'success',
                        "message" => "PostEconomy Create Successfully"
                    ]);
                }else{
                    return response()->json([
                        'status' => 'fail',
                        "message" => "Please Try Again !"
                    ],500);
                }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(PostEconomy $post-economy)
    {
        $this->authorize('update_post-economy');
        $html = view_template_part('post-economy.update',compact('post-economy'));
        return response()->json([
                   'status'=>true,
                   'html'=>$html,
               ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
      * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request , PostEconomy $post-economy)
    {
        $this->authorize('update_post-economy');
        

        
        $posteconomy->title = $request->title;
$posteconomy->desc = $request->desc;

        $post-economy->updated_by = Auth::id();
        $post-economy->updated_at = Carbon::now();
        if ($post-economy->save()) {

                    return response()->json([
                        'status' => 'success',
                        "message" => "post-economy Updated Successfully"
                    ]);
                }else{
                    return response()->json([
                        'status' => 'fail',
                        "message" => "Please Try Again !"
                    ],500);
                }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(PostEconomy $post-economy)
    {
        $this->authorize('delete_post-economy');
        $post-economy->delete();

        return response()->json([
                    'status'=>true,
                    'message' => 'Record deleted successfully!'
                ]);

    }

        public function status_change(PostEconomy $post-economy)
            {
                $this->authorize('update_post-economy');
                if($post-economy->active == 1)
                {
                    $post-economy->active = 0;
                }else{
                    $post-economy->active = 1;
                }
                $post-economy->save();
                return response()->json([
                    'status'=>true,
                    'message' => 'Status Change successfully!'
                ]);
            }
}
