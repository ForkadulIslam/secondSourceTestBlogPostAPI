<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        //Validation
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        //Creat task
        $task = Task::create($request->all());
        return response()->json($task, 201);
    }

    // Update task status
    public function markComplete(Request $request, $id)
    {
        //Validation
        $validator = \Validator::make($request->all(), [
            'is_completed' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        $task = Task::findOrFail($id);
        $task->update(['is_completed' => true]);

        return response()->json($task);
    }

    // Pending task list
    public function pendingTasks()
    {
        $tasks = Task::where('is_completed', false)->get();
        return response()->json($tasks);
    }
}
