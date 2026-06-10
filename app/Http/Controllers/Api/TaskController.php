<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\AddTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     */
    public function index(Request $request)
    {
        $tasks = Task::query()
            ->when(
                $request->status,
                fn($query, $status) => $query->where('status', $status)
            )
            ->when(
                $request->sort,
                fn($query, $sort) => $query->orderBy('due_date', $sort)
            )
            ->paginate($request->get('per_page', 10));

        if ($tasks->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No tasks found',
                'tasks' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'tasks' => $tasks->items(),
            'pagination' => [
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
            ],
        ]);
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(AddTaskRequest $request)
    {
        $task = Task::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Task created successfully.',
            'task' => $task,
        ], 201);
    }

    /**
     * Display the specified task.
     */
    public function show(string $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'task' => $task,
        ]);
    }

    /**
     * Update the specified task in storage.
     */
    public function update(UpdateTaskRequest $request, string $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found',
            ], 404);
        }

        $task->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully.',
            'task' => $task,
        ]);
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found',
            ], 404);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.',
        ]);
    }
}
