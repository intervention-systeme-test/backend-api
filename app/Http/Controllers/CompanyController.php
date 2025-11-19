<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    // Create a company
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'address'     => 'nullable|string',
            'cfe_number'  => 'nullable|string',
        ]);

        $company = Company::create([
            'user_id'     => $request->user()->id,
            'name'        => $request->name,
            'address'     => $request->address,
            'cfe_number'  => $request->cfe_number,
        ]);

        return response()->json($company, 201);
    }

    // Update company
    public function update(Request $request, $id)
    {
        $company = Company::where('user_id', $request->user()->id)->findOrFail($id);

        $company->update($request->only('name', 'address', 'cfe_number'));

        return response()->json($company);
    }

    // Delete company
    public function destroy(Request $request, $id)
    {
        $company = Company::where('user_id', $request->user()->id)->findOrFail($id);
        $company->delete();

        return response()->json(['message' => 'Company deleted']);
    }

    // All companies of the user
    public function index(Request $request)
    {
        return response()->json(
            $request->user()->companies
        );
    }
}
