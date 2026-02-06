<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Repositories\TaskRepository;
use App\Http\Requests\Tasks\ReorderTaskRequest;
use App\Http\Requests\Tasks\SaveTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;

class TaskController extends Controller
{
    private $taskRepository;

    public function __construct()
    {
        //dependency injection
        $this->taskRepository = app(TaskRepository::class);
    }

    public function index()
    {
        $tasks = $this->taskRepository->getAllTasksForUser(auth()->id());
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveTaskRequest $request)
    {
        $validatedData = $request->validated();

        $data = [
            'user_id'       => auth()->id(),
            'priority'      => null,
            'title'         => $validatedData['title'],
            'description'   => $validatedData['description'],
            'due_date'      => $validatedData['due_date'],
        ];

        try {

            $this->taskRepository->save($data);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        
        }
        
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validatedData = $request->validated();

        $data = [
            'title'         => $validatedData['title'],
            'description'   => $validatedData['description'],
            'due_date'      => $validatedData['due_date'],
            'status'        => $validatedData['status'],
        ];

        try {
            $this->taskRepository->update($task->id, $data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {
            $this->taskRepository->delete($task->id);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    /**
     * Reorder
     */
    public function reorderTask(ReorderTaskRequest $request)
    {
        $data = $request->validated();

        $order = collect($data['order'])
            ->sortBy('index')
            ->values();

            DB::beginTransaction();

            try {
                foreach ($order as $row) {
                    $id = $row['id'];
                    $newPriority = $row['index'] + 1;
                    $this->taskRepository->update($id, ['priority' => $newPriority]);
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
            }

        return response()->json(['ok' => true]);
    }
}
