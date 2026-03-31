<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('industry', 'like', "%{$s}%")
                  ->orWhere('city', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('industry')) {
            $query->where('industry', $request->industry);
        }

        $companies = $query->latest()->paginate(10)->withQueryString();
        $stats = [
            'total'    => Company::count(),
            'active'   => Company::where('status', 'active')->count(),
            'inactive' => Company::where('status', 'inactive')->count(),
            'prospect' => Company::where('status', 'prospect')->count(),
        ];
        $industries = Company::select('industry')->distinct()->whereNotNull('industry')->pluck('industry');

        return view('admin.companies.index', compact('companies', 'stats', 'industries'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:150',
            'industry'       => 'nullable|string|max:100',
            'website'        => 'nullable|url|max:255',
            'phone'          => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:255',
            'employees'      => 'nullable|integer|min:1',
            'annual_revenue' => 'nullable|numeric|min:0',
            'city'           => 'nullable|string|max:100',
            'country'        => 'nullable|string|max:100',
            'address'        => 'nullable|string|max:255',
            'status'         => 'required|in:active,inactive,prospect',
            'description'    => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $company = Company::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Company created successfully.',
            'company' => $company,
        ]);
    }

    public function show(Company $company)
    {
        $company->load(['leads', 'deals']);
        return response()->json($company);
    }

    public function edit(Company $company)
    {
        return response()->json($company);
    }

    public function update(Request $request, Company $company)
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:150',
            'industry'       => 'nullable|string|max:100',
            'website'        => 'nullable|url|max:255',
            'phone'          => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:255',
            'employees'      => 'nullable|integer|min:1',
            'annual_revenue' => 'nullable|numeric|min:0',
            'city'           => 'nullable|string|max:100',
            'country'        => 'nullable|string|max:100',
            'address'        => 'nullable|string|max:255',
            'status'         => 'required|in:active,inactive,prospect',
            'description'    => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $company->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Company updated successfully.',
            'company' => $company->fresh(),
        ]);
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return response()->json(['success' => true, 'message' => 'Company deleted successfully.']);
    }
}
