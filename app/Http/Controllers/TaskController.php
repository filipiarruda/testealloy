<?php

namespace App\Http\Controllers;

use App\Jobs\FinishTask;
use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;

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
        // Atualiza o status da tarefa
        $task->finalizado = !$task->finalizado;
        $task->save();
        //Job que finaliza a task em definitivo, com a exclusão após 10 minutos
        FinishTask::dispatch($task)->delay(Carbon::now()->addMinutes(10));
        return response()->json([
            'message' => "Agendamos a remoção da task {$task->id} ."]);
    }
}

