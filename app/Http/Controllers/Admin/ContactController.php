<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        // Check if user can view contacts (all roles can)
        $query = Contact::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('company', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $contacts = $query->latest()->paginate(10)->withQueryString();
        $stats = [
            'total'    => Contact::count(),
            'customer' => Contact::where('status', 'customer')->count(),
            'lead'     => Contact::where('status', 'lead')->count(),
            'prospect' => Contact::where('status', 'prospect')->count(),
        ];

        return view('admin.contacts.index', compact('contacts', 'stats'));
    }

    public function store(Request $request)
    {
        // Check if user can manage customers and leads
        if (!auth()->user()->canManageCustomersAndLeads()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:contacts,email',
            'phone'      => 'nullable|string|max:30',
            'job_title'  => 'nullable|string|max:100',
            'company'    => 'nullable|string|max:150',
            'status'     => 'required|in:customer,lead,prospect,inactive',
            'city'       => 'nullable|string|max:100',
            'country'    => 'nullable|string|max:100',
            'notes'      => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $contact = Contact::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Contact created successfully.',
            'contact' => $contact,
        ]);
    }

    public function show(Request $request, Contact $contact)
    {
        $contact->load(['leads', 'deals', 'tasks']);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($contact);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    public function edit(Contact $contact)
    {
        return response()->json($contact);
    }

    public function update(Request $request, Contact $contact)
    {
        // Check if user can manage customers and leads
        if (!auth()->user()->canManageCustomersAndLeads()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:contacts,email,' . $contact->id,
            'phone'      => 'nullable|string|max:30',
            'job_title'  => 'nullable|string|max:100',
            'company'    => 'nullable|string|max:150',
            'status'     => 'required|in:customer,lead,prospect,inactive',
            'city'       => 'nullable|string|max:100',
            'country'    => 'nullable|string|max:100',
            'notes'      => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $contact->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Contact updated successfully.',
            'contact' => $contact->fresh(),
        ]);
    }

    public function destroy(Contact $contact)
    {
        // Check if user can manage customers and leads
        if (!auth()->user()->canManageCustomersAndLeads()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $contact->delete();
        return response()->json(['success' => true, 'message' => 'Contact deleted successfully.']);
    }

    public function exportCsv()
    {
        // Check if user can manage customers and leads (for export)
        if (!auth()->user()->canManageCustomersAndLeads()) {
            abort(403, 'Unauthorized');
        }

        $contacts = Contact::all();
        $filename = 'contacts_' . now()->format('Y_m_d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($contacts) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Job Title', 'Company', 'Status', 'City', 'Country', 'Notes', 'Created At']);
            foreach ($contacts as $c) {
                fputcsv($handle, [
                    $c->id, $c->first_name, $c->last_name, $c->email,
                    $c->phone, $c->job_title, $c->company, $c->status,
                    $c->city, $c->country, $c->notes, $c->created_at,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
