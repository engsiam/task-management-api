<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Add a new task
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
        ]);

        $task = Task::create([
            'title' => $validated['title'],
            'is_completed' => false,
        ]);
        return response()->json([
            'id' => $task->id,
            'title' => $task->title,
            'is_completed' => $task->is_completed,
            'created_at' => $task->created_at
        ]);
    }
    //Mark a task as completed
    public function markAsCompleted($id)
    {
        $task = Task::findOrFail($id);
        $task->is_completed = true;
        $task->save();
        return response()->json([
            'id' => $task->id,
            'title' => $task->title,
            'is_completed' => $task->is_completed,
            'created_at' => $task->created_at
        ]);
    }
     // Get all pending tasks
    public function getPendingTasks()
    {
        $tasks = Task::where('is_completed', false)->get();
        return response()->json($tasks);
    }
}