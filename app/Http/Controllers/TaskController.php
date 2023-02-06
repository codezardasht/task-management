<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\TaskAssignRequest;
use App\Http\Requests\Task\TaskMovieRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\Task\TaskCollection;
use App\Http\Resources\Task\TaskResource;
use App\Models\Label;
use App\Models\StatusBoard;
use App\Models\Task;
use App\Models\TaskLabel;
use App\Models\TaskStatus;
use App\Models\User;
use App\Notifications\TaskAssigned;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return TaskCollection
     */
    public function index()
    {
       $tasks = Task::checkrole()->search()->get();

       return new TaskCollection($tasks);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Task\StoreTaskRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTaskRequest $request)
    {
//        $this->authorize('create_task');
       $result = DB::transaction(function () use ($request){
           $task = new Task;
           $task->title = $request->title;
           $task->board_id = $request->board_id;
           $task->status_board_id = $request->status_board_id;
           $task->current_status = $this->get_status_name($request->status_board_id);
           $task->created_by = \auth()->id();
           $task->created_at = Carbon::now();
           $task->save();
           $this->store_task_status($task->id , $task->status_board_id);
           return $task;
       });

        return ($result)
            ? store_message("Task" , $result)
            : try_again_message();
    }

    public function store_task_status($task_id , $status_board_id)
    {
       return DB::transaction(function () use ($task_id , $status_board_id){
           $newTaskStatus = new TaskStatus;
           $newTaskStatus->task_id = $task_id;
           $newTaskStatus->status_board_id = $status_board_id;
           $newTaskStatus->created_by = \auth()->id();
           $newTaskStatus->created_at = Carbon::now();
           $newTaskStatus->save();

           return $newTaskStatus;
       }) ;
    }

    public function get_status_name($status_id)
    {

        return DB::transaction(function () use ($status_id) {
            $status = StatusBoard::findOrFail($status_id);

            return $status->name;
        });


    }

    public function get_status($status_id)
    {

        return DB::transaction(function () use ($status_id) {
            $status = StatusBoard::findOrFail($status_id);

            return $status;
        });


    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Task $task
     * @return TaskResource
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Task\UpdateTaskRequest  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update_task');
        $task->title = $request->title;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->updated_by = \auth()->id();
        $task->updated_at = Carbon::now();
        ($request->hasFile('image')) ? store_image($request , $task) : NULL;
        ($request->label != '') ? $this->store_label($request->label , $task) : NULL;


        return $task->save()
            ? update_message("Task" , $task)
            : try_again_message();
    }

    public function store_label($labels , $task)
    {
        $result = DB::transaction(function () use ($labels , $task) {
            foreach ($labels as $label )
            {
               $this->check_and_store_label($label , $task);
            }

            return $labels;
        });

        return $result;
    }

    public function check_and_store_label($labels , $task)
    {
        $result = DB::transaction(function () use ($labels , $task) {


            $label = Label::firstOrNew(['name' => $labels]);

            if (!$label->exists) {
                $label->created_by = auth('api')->id();
                $label->created_at = Carbon::now();
                $label->save();
            }

             $this->store_label_to_task($label->id , $task->id);

            return $label;
        });

        return $result;
    }

    public function store_label_to_task($label_id , $task_id)
    {
        $result = DB::transaction(function () use ($label_id , $task_id) {

            $taskLable = TaskLabel::firstOrNew(['label_id' => $label_id , 'task_id' => $task_id]);

            if (!$taskLable->exists) {
                $taskLable->created_by = auth('api')->id();
                $taskLable->created_at = Carbon::now();
                $taskLable->save();
            }

            return $taskLable;
        });

        return $result;
    }


    public function assign(TaskAssignRequest $request , Task $task)
    {
        $check = $this->check_task_assign($task);
        $check_role = $this->check_task_role($task, $request->user_id);



        if ($check || !$check_role) {
            return response()->json(['message' => 'Task is already assigned to someone else'], 400);
        }

        $result = DB::transaction(function () use ($request, $task) {


            $status = $task->users()->toggle(request('user_id') , ['created_by' => auth()->id()]);



            return $status;

        });


        return ($result) ? assign_message_task() : try_again_message();

    }

    public function movie(TaskMovieRequest $request , Task $task)
    {
        $check_role = $this->check_task_role($task, auth()->id());


        if ( !$check_role) {
            return response()->json(['message' => 'Unauthorized to movie task'], 400);
        }else if ($request->status_board_id == $task->status_board_id)
        {
            return response()->json(['message' => 'This is same status'], 400);
        }

        $result = DB::transaction(function () use ($request, $task) {
            $this->store_task_status($task->id , $request->status_board_id);
            $task->status_board_id = $request->status_board_id;
            $task->current_status = $this->get_status_name($request->status_board_id);
            $task->save();

            return $task;
        });


        return ($result) ? movie_message($this->get_status_name($request->status_board_id)) : try_again_message();

    }
    public function check_task_assign($task)
    {
        $getStatusName = $this->get_status($task->status_board_id);

        return ($getStatusName->is_assign == 0);

    }

    public function check_task_role($task, $user_id)
    {
        $getStatusName = $this->get_status($task->status_board_id);

        $getRole = Role::whereIn('id', explode(',' , $getStatusName->role_ids))->get();
        $userAssignRole = User::find($user_id)?->roles?->pluck('id');

        $result = $userAssignRole->intersect($getRole->pluck('id'))->isNotEmpty();
        if ($result) {
            return true;
        } else {
            return false;
        }

    }

    public function duy_date()
    {
        $dateNow = Carbon::now()->format('Y-m-d');

        //get Task Due Date
        $taskDuyDate = Task::whereDate('due_date','<=' , $dateNow)->get();

        return new TaskCollection($taskDuyDate);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete_task');
        return $task->delete()
            ? delete_message("TASK")
            : try_again_message();
    }
}
