<?php



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
