<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        return response()->json([
            'tasks' => Task::all(),
        ]);
    }

    public function store(Request $request)
    {
        $task = Task::create($request->all());

        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task)
    {
        $task->update($request->all());

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(true);
    }

    public function updateStatus(Request $request, Task $task)
    {
        $task->update(['finalizado' => $request->input('finalizado')]);

        return response()->json($task);
    }
}

