<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Contracts\Service\Attribute\Required;
use Spatie\QueryBuilder\QueryBuilder;

class TaskController extends Controller
{
    public function index()
    {


        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters('is_done')
            ->defaultSort('-created_at')
            ->allowedSorts('title', 'is_done', 'created_at')
            ->paginate();
        return new TaskCollection($tasks);
    }

    public function show(Task $task)
    {
        return new TaskResource($task);
    }
    // anotherway
    //  public function show ($id)
    // {
    //     $task = Task::find($id);

    //     return new TaskResource($task);
    // }

    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();

        $task = Auth::user()->tasks()->create($validated);
        
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validated = $request->validated();

        $task->update($validated);

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {


        $task->delete();

        $success = [
            'status' => 200,
            'message' => "Task Removed"
        ];

        $wrong = [
            'status' => 404,
            'message' => "Task not found"
        ];

        if ($task) {
            $task->delete();
            return response()->json($success, 200);
        } else {
            return response()->json($wrong, 404);
        }
    }
}
