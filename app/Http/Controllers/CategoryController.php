<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Session;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('view_category');
        return view('category.index');
    }

     public function data(Request $request)
        {
           $this->authorize('view_category');
            if ($request->ajax()) {
                        $category = Category::all();

                        return Datatables::of($category)
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
            															<input type="checkbox" class="form-check-input '.$checkbox_color.' checkbox_change_status " '.$status_check.' data-route="'.route('category.status_change',[$data->id]).'">
            															<span class="form-check-label '.$status_label.'">'.$status.'</span>
            														</label>';

                                return $checkbox;


                            })
                            ->addColumn('action', function ($data) {
                                $button = '';



                                $button .= '<div>';

                                if (\Auth::user()->can('update_category')) {
                                    $button .= '<a href="#" class="btn btn-sm btn-outline-indigo rounded-pill p-2" id="edit" data-id="' . $data->id . '" ><i class="fa fa-edit edit_icon_' . $data->id . '"></i> </a>';

                                }
                                if (\Auth::user()->can('delete_category')) {
                                    $button .= '<a href="#"  id="delete" data-id="' . $data->id . '" data-route="' . route("category.destroy", [$data->id]) . '" class="btn btn-sm btn-outline-danger rounded-pill p-2 ms-1"><i class="fa fa-trash"></i> </a>';
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
         $this->authorize('add_category');
        

        $category = new  Category();
        $category->title = $request->title;
$category->desc = $request->desc;

        $category->created_by = Auth::id();
        $category->created_at = Carbon::now();
         
           if ($category->save()) {
                    return response()->json([
                        'status' => 'success',
                        "message" => "Category Create Successfully"
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
    public function edit(Category $category)
    {
        $this->authorize('update_category');
        $html = view_template_part('category.update',compact('category'));
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
    public function update(Request $request , Category $category)
    {
        $this->authorize('update_category');
        

        
        $category->title = $request->title;
$category->desc = $request->desc;

        $category->updated_by = Auth::id();
        $category->updated_at = Carbon::now();
        if ($category->save()) {

                    return response()->json([
                        'status' => 'success',
                        "message" => "category Updated Successfully"
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
    public function destroy(Category $category)
    {
        $this->authorize('delete_category');
        $category->delete();

        return response()->json([
                    'status'=>true,
                    'message' => 'Record deleted successfully!'
                ]);

    }

        public function status_change(Category $category)
            {
                $this->authorize('update_category');
                if($category->active == 1)
                {
                    $category->active = 0;
                }else{
                    $category->active = 1;
                }
                $category->save();
                return response()->json([
                    'status'=>true,
                    'message' => 'Status Change successfully!'
                ]);
            }
}
