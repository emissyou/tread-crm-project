<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\Deal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Task::class);

        $query = Task::with(['contact', 'lead', 'deal', 'assignedUser']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tasks     = $query->orderBy('due_date')->paginate(15)->withQueryString();
        $leads     = Lead::orderBy('name')->get();
        $deals     = Deal::orderBy('title')->get();
        $users     = User::orderBy('name')->get();

        $stats = [
            'total'       => Task::count(),
            'pending'     => Task::where('status', 'pending')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'completed'   => Task::where('status', 'completed')->count(),
            'overdue'     => Task::where('status', '!=', 'completed')->where('due_date', '<', now())->count(),
        ];

        return view('admin.tasks.index', compact('tasks', 'leads', 'deals', 'users', 'stats'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Task::class);

        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string',
            'status'      => 'required|in:pending,in_progress,completed,cancelled',
            'priority'    => 'required|in:low,medium,high',
            'due_date'    => 'nullable|date',
            'due_time'    => 'nullable',
            'contact_id'  => 'nullable|exists:contacts,id',
            'lead_id'     => 'nullable|exists:leads,id',
            'deal_id'     => 'nullable|exists:deals,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $data['created_by'] = auth()->id();

        $task = Task::create($data);
        $task->load(['contact', 'lead', 'deal', 'assignedUser']);

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully.',
            'task'    => $task,
        ]);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        $task->load(['contact', 'lead', 'deal', 'assignedUser', 'createdBy']);
        return response()->json($task);
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        return response()->json($task);
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string',
            'status'      => 'required|in:pending,in_progress,completed,cancelled',
            'priority'    => 'required|in:low,medium,high',
            'due_date'    => 'nullable|date',
            'due_time'    => 'nullable',
            'contact_id'  => 'nullable|exists:contacts,id',
            'lead_id'     => 'nullable|exists:leads,id',
            'deal_id'     => 'nullable|exists:deals,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $task->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully.',
            'task'    => $task->fresh()->load(['contact', 'lead', 'deal', 'assignedUser']),
        ]);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();
        return response()->json(['success' => true, 'message' => 'Task deleted successfully.']);
    }

    public function toggleComplete(Task $task)
    {
        $this->authorize('update', $task);

        $task->update([
            'status' => $task->status === 'completed' ? 'pending' : 'completed',
        ]);
        return response()->json(['success' => true, 'status' => $task->fresh()->status]);
    }
}
