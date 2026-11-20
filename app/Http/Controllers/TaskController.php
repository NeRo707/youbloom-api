<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req): View
    {
        $search = $req->input('search');
        $status = $req->input('status');
        $perPage = 10;

        $totalCount = Task::count();
        $pendingCount = Task::where('status', Task::STATUS_PENDING)->count();
        $completedCount = Task::where('status', Task::STATUS_COMPLETED)->count();

        $tasks = Task::query()
            ->search($search)
            ->status($status)
            ->ordered()
            ->paginate($perPage)
            ->appends($req->query());

        return view('tasks.index', compact(
            'tasks',
            'totalCount',
            'pendingCount',
            'completedCount',
            'search',
            'status'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Auto-assign next drag-and-drop order position
        $maxOrder = Task::max('order') ?? 0;
        $validated['order'] = $maxOrder + 1;
        $task = Task::create($validated);
        return redirect()
            ->route('tasks.index')
            ->with('success', "Task \"{$task->title}\" was successfully created.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): View
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): View
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()
            ->route('tasks.index')
            ->with('success', "Task \"{$task->title}\" was successfully updated.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $title = $task->title;
        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', "Task \"{$title}\" was successfully deleted.");
    }

    // Switches between Pending and Completed.
    public function toggleStatus(Request $request, Task $task): JsonResponse|RedirectResponse
    {
        $newStatus = $task->isCompleted() ? Task::STATUS_PENDING : Task::STATUS_COMPLETED;
        $task->update(['status' => $newStatus]);
        $message = "Task \"{$task->title}\" marked as {$newStatus}.";
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $task->status,
                'is_completed' => $task->isCompleted(),
            ]);
        }
        return back()->with('success', $message);
    }

    // Persist Drag-and-Drop Task Reordering in Database.
    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'task_ids' => ['required', 'array'],
            'task_ids.*' => ['required', 'integer', 'exists:tasks,id'],
        ]);
        DB::transaction(function () use ($validated) {
            foreach ($validated['task_ids'] as $position => $id) {
                Task::where('id', $id)->update(['order' => $position + 1]);
            }
        });
        return response()->json([
            'success' => true,
            'message' => 'Task display order updated successfully.',
        ]);
    }
}
