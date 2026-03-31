<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Contact;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with(['contact', 'company', 'assignedUser']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('source', 'like', "%{$s}%")
                  ->orWhereHas('contact', fn($q2) => $q2->where('first_name', 'like', "%{$s}%")->orWhere('last_name', 'like', "%{$s}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $leads    = $query->latest()->paginate(10)->withQueryString();
        $contacts = Contact::orderBy('first_name')->get();
        $companies = Company::orderBy('name')->get();
        $users    = User::where('is_active', true)->orderBy('name')->get();

        $stats = [
            'total'       => Lead::count(),
            'new'         => Lead::where('status', 'new')->count(),
            'contacted'   => Lead::where('status', 'contacted')->count(),
            'negotiating' => Lead::where('status', 'negotiating')->count(),
            'closed'      => Lead::where('status', 'closed')->count(),
            'lost'        => Lead::where('status', 'lost')->count(),
        ];

        return view('admin.leads.index', compact('leads', 'contacts', 'companies', 'users', 'stats'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'          => 'required|string|max:200',
            'contact_id'     => 'nullable|exists:contacts,id',
            'company_id'     => 'nullable|exists:companies,id',
            'source'         => 'nullable|string|max:100',
            'status'         => 'required|in:new,contacted,negotiating,closed,lost',
            'priority'       => 'required|in:low,medium,high',
            'value'          => 'nullable|numeric|min:0',
            'notes'          => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'assigned_to'    => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $lead = Lead::create($request->all());
        $lead->load(['contact', 'company', 'assignedUser']);

        return response()->json([
            'success' => true,
            'message' => 'Lead created successfully.',
            'lead'    => $lead,
        ]);
    }

    public function show(Lead $lead)
    {
        $lead->load(['contact', 'company', 'assignedUser', 'deals', 'tasks']);
        return response()->json($lead);
    }

    public function edit(Lead $lead)
    {
        return response()->json($lead);
    }

    public function update(Request $request, Lead $lead)
    {
        $validator = Validator::make($request->all(), [
            'title'          => 'required|string|max:200',
            'contact_id'     => 'nullable|exists:contacts,id',
            'company_id'     => 'nullable|exists:companies,id',
            'source'         => 'nullable|string|max:100',
            'status'         => 'required|in:new,contacted,negotiating,closed,lost',
            'priority'       => 'required|in:low,medium,high',
            'value'          => 'nullable|numeric|min:0',
            'notes'          => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'assigned_to'    => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $lead->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Lead updated successfully.',
            'lead'    => $lead->fresh()->load(['contact', 'company', 'assignedUser']),
        ]);
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return response()->json(['success' => true, 'message' => 'Lead deleted successfully.']);
    }
}
