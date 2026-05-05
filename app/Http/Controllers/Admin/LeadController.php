<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use App\Models\Customer; // Add this import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Lead::class);

        $query = Lead::with(['customer', 'assignedUser']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('source', 'like', "%{$s}%")
                  ->orWhereHas('customer', fn($q2) => $q2->where('first_name', 'like', "%{$s}%")->orWhere('last_name', 'like', "%{$s}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $leads    = $query->latest()->paginate(10)->withQueryString();
        $users    = User::orderBy('name')->get();

        $stats = [
            'total'       => Lead::count(),
            'new'         => Lead::where('status', 'new')->count(),
            'contacted'   => Lead::where('status', 'contacted')->count(),
            'negotiating' => Lead::where('status', 'negotiating')->count(),
            'closed'      => Lead::where('status', 'closed')->count(),
            'lost'        => Lead::where('status', 'lost')->count(),
        ];

        return view('admin.leads.index', compact('leads', 'users', 'stats'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Lead::class);

        // ❌ REMOVE THIS LINE:
        // dd("test");

        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|max:200',
            'email'           => 'nullable|email|max:100',
            'phone'           => 'nullable|string|max:20',
            'customer_id'     => 'nullable|exists:customers,id',
            'source'          => 'nullable|string|max:100',
            'status'          => 'required|in:new,contacted,negotiating,closed,lost',
            'priority'        => 'required|in:low,medium,high',
            'expected_value'  => 'nullable|numeric|min:0',
            'notes'           => 'nullable|string',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $lead = Lead::create($request->all());
        $lead->load(['customer', 'assignedUser']);

        return response()->json([
            'message' => 'Lead created successfully.',
            'lead'    => $lead,
        ]);
    }


    public function show(Lead $lead)
    {
        $this->authorize('view', $lead);

        $lead->load(['customer', 'assignedUser']);
        return response()->json($lead);
    }

    public function edit(Lead $lead)
    {
        $this->authorize('update', $lead);

        return response()->json($lead);
    }

    public function update(Request $request, Lead $lead)
    {
        $this->authorize('update', $lead);
        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|max:200',
            'email'           => 'nullable|email|max:100',
            'phone'           => 'nullable|string|max:20',
            'customer_id'     => 'nullable|exists:customers,id',
            'source'          => 'nullable|string|max:100',
            'status'          => 'required|in:new,contacted,negotiating,closed,lost',
            'priority'        => 'required|in:low,medium,high',
            'expected_value'  => 'nullable|numeric|min:0',
            'notes'           => 'nullable|string',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $lead->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Lead updated successfully.',
            'lead'    => $lead->fresh()->load(['customer', 'assignedUser']),
        ]);
    }

    public function destroy(Lead $lead)
    {
        $this->authorize('delete', $lead);

        $lead->delete();
        return response()->json(['success' => true, 'message' => 'Lead deleted successfully.']);
    }
}
