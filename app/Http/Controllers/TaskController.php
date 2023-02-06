<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Requests\TaskAssignRequest;
use App\Http\Resources\Task\TaskResource;
use App\Models\Label;
use App\Models\Task;
use App\Models\TaskLabel;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class TaskController extends Controller
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
     * @param  \App\Http\Requests\Task\StoreTaskRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTaskRequest $request)
    {
        $task = new Task;
        $task->title = $request->title;
        $task->board_id = $request->board_id;
        $task->list_id = $request->list_id;
        $task->current_status = "Todo";
        $task->created_by = \auth()->id();
        $task->created_at = Carbon::now();
        return $task->save()
            ? store_message("Task" , $task)
            : try_again_message();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
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
        $assigned_users = $task->users;
        if ($assigned_users->count() > 0) {
            // Remove the existing task
            $task->users()->detach($assigned_users->first()->id);
        }

        // Make the new task
        $task->users()->attach($request->user_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Task $task)
    {
        return $task->delete()
            ? delete_message("TASK")
            : try_again_message();
    }
}
