<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Contact;
use App\Models\Company;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DealController extends Controller
{
    public function index(Request $request)
    {
        $query = Deal::with(['contact', 'company', 'lead', 'assignedUser']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhereHas('contact', fn($q2) => $q2->where('first_name', 'like', "%{$s}%")->orWhere('last_name', 'like', "%{$s}%"))
                  ->orWhereHas('company', fn($q2) => $q2->where('name', 'like', "%{$s}%"));
            });
        }

        if ($request->filled('stage')) {
            $query->where('stage', $request->stage);
        }

        $deals     = $query->latest()->paginate(10)->withQueryString();
        $contacts  = Contact::orderBy('first_name')->get();
        $companies = Company::orderBy('name')->get();
        $leads     = Lead::orderBy('title')->get();
        $users     = User::orderBy('name')->get();

        $stats = [
            'total'       => Deal::count(),
            'total_value' => Deal::sum('value'),
            'won'         => Deal::where('stage', 'closed_won')->count(),
            'won_value'   => Deal::where('stage', 'closed_won')->sum('value'),
            'lost'        => Deal::where('stage', 'closed_lost')->count(),
            'active'      => Deal::whereNotIn('stage', ['closed_won', 'closed_lost'])->count(),
        ];

        // Kanban data
        $kanban = [
            'prospecting'   => Deal::where('stage', 'prospecting')->with('contact')->get(),
            'qualification' => Deal::where('stage', 'qualification')->with('contact')->get(),
            'proposal'      => Deal::where('stage', 'proposal')->with('contact')->get(),
            'negotiation'   => Deal::where('stage', 'negotiation')->with('contact')->get(),
            'closed_won'    => Deal::where('stage', 'closed_won')->with('contact')->get(),
            'closed_lost'   => Deal::where('stage', 'closed_lost')->with('contact')->get(),
        ];

        return view('admin.deals.index', compact('deals', 'contacts', 'companies', 'leads', 'users', 'stats', 'kanban'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'               => 'required|string|max:200',
            'contact_id'          => 'nullable|exists:contacts,id',
            'company_id'          => 'nullable|exists:companies,id',
            'lead_id'             => 'nullable|exists:leads,id',
            'value'               => 'nullable|numeric|min:0',
            'stage'               => 'required|in:prospecting,qualification,proposal,negotiation,closed_won,closed_lost',
            'probability'         => 'nullable|integer|min:0|max:100',
            'expected_close_date' => 'nullable|date',
            'notes'               => 'nullable|string',
            'assigned_to'         => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $deal = Deal::create($request->all());
        $deal->load(['contact', 'company', 'assignedUser']);

        return response()->json([
            'success' => true,
            'message' => 'Deal created successfully.',
            'deal'    => $deal,
        ]);
    }

    public function show(Deal $deal)
    {
        $deal->load(['contact', 'company', 'lead', 'assignedUser', 'tasks']);
        return response()->json($deal);
    }

    public function edit(Deal $deal)
    {
        return response()->json($deal);
    }

    public function update(Request $request, Deal $deal)
    {
        $validator = Validator::make($request->all(), [
            'title'               => 'required|string|max:200',
            'contact_id'          => 'nullable|exists:contacts,id',
            'company_id'          => 'nullable|exists:companies,id',
            'lead_id'             => 'nullable|exists:leads,id',
            'value'               => 'nullable|numeric|min:0',
            'stage'               => 'required|in:prospecting,qualification,proposal,negotiation,closed_won,closed_lost',
            'probability'         => 'nullable|integer|min:0|max:100',
            'expected_close_date' => 'nullable|date',
            'notes'               => 'nullable|string',
            'assigned_to'         => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (in_array($request->stage, ['closed_won', 'closed_lost']) && !$deal->closed_date) {
            $deal->closed_date = now();
        }

        $deal->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Deal updated successfully.',
            'deal'    => $deal->fresh()->load(['contact', 'company', 'assignedUser']),
        ]);
    }

    public function destroy(Deal $deal)
    {
        $deal->delete();
        return response()->json(['success' => true, 'message' => 'Deal deleted successfully.']);
    }

    public function updateStage(Request $request, Deal $deal)
    {
        $request->validate(['stage' => 'required|in:prospecting,qualification,proposal,negotiation,closed_won,closed_lost']);
        $deal->update(['stage' => $request->stage]);
        return response()->json(['success' => true, 'message' => 'Stage updated.']);
    }
}
