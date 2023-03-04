<?php

if (! function_exists('str_plural')) {
    function str_plural($value, $count = 2)
    {
        return Illuminate\Support\Str::plural($value, $count);
    }
}

if (! function_exists('snake_case')) {
    function snake_case($value, $delimiter = '_')
    {
        return Illuminate\Support\Str::snake($value, $delimiter);
    }
}

if (!function_exists('delete_message')) {
    function delete_message($title)
    {

        return response()->json([
            'status' => true,
            "message" => "$title Deleted Successfully",
        ]);
    }
}

if (!function_exists('assign_message')) {
    function assign_message($message)
    {

        return response()->json([
            'status' => false,
            "message" => $message,
        ],422);
    }
}

if (!function_exists('assign_message_task')) {
    function assign_message_task()
    {

        return response()->json([
            'status' => true,
            "message" => "Successfully assigned",
        ]);
    }
}

if (!function_exists('movie_message')) {
    function movie_message($status)
    {

        return response()->json([
            'status' => true,
            "message" => "Successfully Movie Task To $status",
        ]);
    }
}


if (!function_exists('try_again_message')) {
    function try_again_message()
    {

       return response()->json([
            'status' => false,
            "message" => "Please Try Again !",
        ], 403);
    }
}

if (!function_exists('store_message')) {
    function store_message($title , $data = null)
    {
        $response = [
            'status' => true,
            'message' => "Create $title Successfully",
        ];
        (!empty($data)) ? $response['data'] = $data : "";

        return response()->json($response, 201);

    }
}

if (!function_exists('store_image')) {
    function store_image( $request , $task )
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('images', $fileName, 'public');
            $task->image = $path;
            $task->save();
            return $path;
        }

    }
}

if (!function_exists('update_message')) {
    function update_message($title , $data = null)
    {

        $response = [
            'status' => true,
            "message" => "Update $title Successfully",
        ];
        (!empty($data)) ? $response['data'] = $data : "";

        return response()->json($response, 201);
    }
}
