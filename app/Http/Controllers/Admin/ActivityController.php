<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Customer;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['customer', 'lead', 'createdBy']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('description', 'like', "%{$s}%")
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

        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('lead_id')) {
            $query->where('lead_id', $request->lead_id);
        }

        if ($request->filled('date_from')) {
            $query->where('activity_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('activity_date', '<=', $request->date_to);
        }

        $activities = $query->latest('activity_date')->paginate(15)->withQueryString();

        $stats = [
            'total' => Activity::count(),
            'calls' => Activity::where('activity_type', 'call')->count(),
            'emails' => Activity::where('activity_type', 'email')->count(),
            'meetings' => Activity::where('activity_type', 'meeting')->count(),
            'notes' => Activity::where('activity_type', 'note')->count(),
            'recent' => Activity::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        $customers = Customer::select('id', 'first_name', 'last_name', 'email')->get();
        $leads = Lead::select('id', 'name', 'email')->get();

        return view('admin.activities.index', compact('activities', 'stats', 'customers', 'leads'));
    }

    public function create(Request $request)
    {
        $customers = Customer::select('id', 'first_name', 'last_name', 'email')->get();
        $leads = Lead::select('id', 'name', 'email')->get();

        $customerId = $request->get('customer_id');
        $leadId = $request->get('lead_id');

        return view('admin.activities.create', compact('customers', 'leads', 'customerId', 'leadId'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'nullable|exists:customers,id',
            'lead_id' => 'nullable|exists:leads,id',
            'activity_type' => 'required|in:call,email,meeting,note,task,follow_up,other',
            'description' => 'required|string',
            'date' => 'required|date',
            'metadata' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Ensure either customer or lead is selected
        if (!$request->customer_id && !$request->lead_id) {
            return response()->json(['errors' => ['customer_id' => ['Either customer or lead must be selected.']]], 422);
        }

        $activity = Activity::create([
            'customer_id' => $request->customer_id,
            'lead_id' => $request->lead_id,
            'user_id' => auth()->id(),
            'activity_type' => $request->activity_type,
            'description' => $request->description,
            'activity_date' => $request->date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Activity logged successfully.',
            'activity' => $activity->load(['customer', 'lead', 'createdBy']),
        ]);
    }

    public function show(Activity $activity)
    {
        $activity->load(['customer', 'lead', 'createdBy']);
        return response()->json($activity);
    }

    public function edit(Activity $activity)
    {
        $customers = Customer::select('id', 'first_name', 'last_name', 'email')->get();
        $leads = Lead::select('id', 'name', 'email')->get();

        return view('admin.activities.edit', compact('activity', 'customers', 'leads'));
    }

    public function update(Request $request, Activity $activity)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'nullable|exists:customers,id',
            'lead_id' => 'nullable|exists:leads,id',
            'activity_type' => 'required|in:call,email,meeting,note,task,follow_up,other',
            'description' => 'required|string',
            'date' => 'required|date',
            'metadata' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Ensure either customer or lead is selected
        if (!$request->customer_id && !$request->lead_id) {
            return response()->json(['errors' => ['customer_id' => ['Either customer or lead must be selected.']]], 422);
        }

        $activity->update([
            'customer_id' => $request->customer_id,
            'lead_id' => $request->lead_id,
            'activity_type' => $request->activity_type,
            'description' => $request->description,
            'date' => $request->date,
            'metadata' => $request->metadata,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Activity updated successfully.',
            'activity' => $activity->fresh()->load(['customer', 'lead', 'createdBy']),
        ]);
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();
        return response()->json(['success' => true, 'message' => 'Activity deleted successfully.']);
    }

    public function getForCustomer(Customer $customer)
    {
        $activities = $customer->activities()
            ->with('createdBy')
            ->latest('date')
            ->take(10)
            ->get();

        return response()->json($activities);
    }

    public function getForLead(Lead $lead)
    {
        $activities = $lead->activities()
            ->with('createdBy')
            ->latest('date')
            ->take(10)
            ->get();

        return response()->json($activities);
    }
}