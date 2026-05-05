<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FollowUp;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FollowUpController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', FollowUp::class);

        $query = FollowUp::with(['customer', 'lead', 'user']); // ← Uses 'user' relationship

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%")
                  ->orWhereHas('customer', function ($cq) use ($s) {
                      $cq->where('first_name', 'like', "%{$s}%")
                         ->orWhere('last_name', 'like', "%{$s}%")
                         ->orWhere('email', 'like', "%{$s}%");
                  })
                  ->orWhereHas('lead', function ($lq) use ($s) {
                      $lq->where('name', 'like', "%{$s}%")
                         ->orWhere('email', 'like', "%{$s}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) { // ← Fixed filter name
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('filter')) {
            switch ($request->filter) {
                case 'overdue':
                    $query->overdue();
                    break;
                case 'due_today':
                    $query->dueToday();
                    break;
                case 'due_soon':
                    $query->dueSoon();
                    break;
                case 'completed':
                    $query->completed();
                    break;
                default:
                    $query->pending();
            }
        } else {
            $query->pending();
        }

        $followUps = $query->orderBy('due_date')->paginate(10)->withQueryString();

        $stats = [
            'total' => FollowUp::count(),
            'pending' => FollowUp::pending()->count(),
            'completed' => FollowUp::completed()->count(),
            'overdue' => FollowUp::overdue()->count(),
        ];

        $users = User::orderBy('name')->get();
        $customers = Customer::select('id', 'first_name', 'last_name', 'email')->orderBy('first_name')->get();
        $leads = Lead::select('id', 'name', 'email')->orderBy('name')->get();

        return view('admin.follow-ups.index', compact('followUps', 'stats', 'users', 'customers', 'leads'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', FollowUp::class);

        $validator = Validator::make($request->all(), [
            'customer_id' => 'nullable|exists:customers,id',
            'lead_id' => 'nullable|exists:leads,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date|after:now',
            'user_id' => 'nullable|exists:users,id', // ← Matches your model
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!$request->customer_id && !$request->lead_id) {
            return response()->json(['errors' => ['relation' => 'Either customer or lead must be selected.']], 422);
        }

        $followUp = FollowUp::create([
            'customer_id' => $request->customer_id,
            'lead_id' => $request->lead_id,
            'user_id' => $request->user_id ?? auth()->id(), // ← Matches your model
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Follow-up created successfully.',
            'followUp' => $followUp->load(['customer', 'lead', 'user']) // ← Uses 'user'
        ]);
    }

    public function show(FollowUp $followUp)
    {
        $this->authorize('view', $followUp);

        $followUp->load(['customer', 'lead', 'user']); // ← Uses 'user'
        return response()->json($followUp);
    }

    public function update(Request $request, FollowUp $followUp)
    {
        $this->authorize('update', $followUp);
        $validator = Validator::make($request->all(), [
            'customer_id' => 'nullable|exists:customers,id',
            'lead_id' => 'nullable|exists:leads,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'user_id' => 'nullable|exists:users,id', // ← Matches your model
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!$request->customer_id && !$request->lead_id) {
            return response()->json(['errors' => ['relation' => 'Either customer or lead must be selected.']], 422);
        }

        $followUp->update([
            'customer_id' => $request->customer_id,
            'lead_id' => $request->lead_id,
            'user_id' => $request->user_id, // ← Matches your model
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Follow-up updated successfully.',
            'followUp' => $followUp->fresh()->load(['customer', 'lead', 'user']) // ← Uses 'user'
        ]);
    }

    public function destroy(FollowUp $followUp)
    {
        $this->authorize('delete', $followUp);

        $followUp->delete();
        return response()->json(['success' => true, 'message' => 'Follow-up deleted successfully.']);
    }

    public function toggleComplete(FollowUp $followUp)
    {
        $this->authorize('update', $followUp);
        if ($followUp->status === 'completed') {
            $followUp->update(['status' => 'pending', 'completed_at' => null]);
            $message = 'Follow-up marked as pending.';
        } else {
            $followUp->markAsCompleted();
            $message = 'Follow-up marked as completed.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }
}