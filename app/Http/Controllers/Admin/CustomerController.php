<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

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

        if ($request->filled('assigned_user_id')) {
            $query->where('assigned_user_id', $request->assigned_user_id);
        }

        $customers = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total'    => Customer::count(),
            'customer' => Customer::where('status', 'customer')->count(),
            'lead'     => Customer::where('status', 'lead')->count(),
            'prospect' => Customer::where('status', 'prospect')->count(),
            'inactive' => Customer::where('status', 'inactive')->count(),
        ];

        $users = User::orderBy('name')->get();

        return view('admin.customers.index', compact('customers', 'stats', 'users'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('admin.customers.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'email'            => 'required|email|unique:customers,email',
            'phone'            => 'nullable|string|max:30',
            'company'          => 'nullable|string|max:150',
            'address'          => 'nullable|string',
            'status'           => 'required|in:customer,lead,prospect,inactive',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $customer = Customer::create($request->all());

        return response()->json([
            'success'  => true,
            'message'  => 'Customer created successfully.',
            'customer' => $customer,
        ]);
    }

    // ✅ FIXED SHOW (SAFE FOR AJAX)
    public function show(Request $request, Customer $customer)
    {
        return response()->json([
            'success'  => true,
            'customer' => $customer
        ]);
    }

    public function edit(Customer $customer)
    {
        $users = User::orderBy('name')->get();
        return view('admin.customers.edit', compact('customer', 'users'));
    }

    // ✅ FIXED UPDATE (AJAX SAFE)
    public function update(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(), [
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'email'            => 'required|email|unique:customers,email,' . $customer->id,
            'phone'            => 'nullable|string|max:30',
            'company'          => 'nullable|string|max:150',
            'address'          => 'nullable|string',
            'status'           => 'required|in:customer,lead,prospect,inactive',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $customer->update($request->all());

        return response()->json([
            'success'  => true,
            'message'  => 'Customer updated successfully.',
            'customer' => $customer->fresh(),
        ]);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully.'
        ]);
    }

    public function exportCsv()
    {
        $customers = Customer::all();
        $filename  = 'customers_' . now()->format('Y_m_d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($customers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['First Name', 'Last Name', 'Email', 'Phone', 'Company', 'Status', 'Assigned User', 'Created At']);

            foreach ($customers as $c) {
                fputcsv($handle, [
                    $c->first_name,
                    $c->last_name,
                    $c->email,
                    $c->phone,
                    $c->company,
                    $c->status,
                    optional($c->assignedUser)->name,
                    $c->created_at,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}