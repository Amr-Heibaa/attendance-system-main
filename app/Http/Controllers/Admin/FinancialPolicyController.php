<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinancialPolicy;
use Illuminate\Http\Request;

class FinancialPolicyController extends Controller
{
    public function index()
    {
        $policies = FinancialPolicy::latest()->paginate(15);
        return view('admin.financial-policies.index', compact('policies'));
    }

    public function create()
    {
        return view('admin.financial-policies.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:late,absence,early_leave',
            'minutes_from' => 'nullable|integer|min:0',
            'minutes_to' => 'nullable|integer|min:0|gte:minutes_from',
            'penalty_type' => 'required|in:fixed,percent,warning',
            'penalty_value' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        FinancialPolicy::create($data);

        return redirect()->route('admin.financial-policies.index')
            ->with('success', 'تم إضافة الشريحة المالية بنجاح');
    }

    public function edit(FinancialPolicy $financialPolicy)
    {
        return view('admin.financial-policies.edit', compact('financialPolicy'));
    }

    public function update(Request $request, FinancialPolicy $financialPolicy)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:late,absence,early_leave',
            'minutes_from' => 'nullable|integer|min:0',
            'minutes_to' => 'nullable|integer|min:0|gte:minutes_from',
            'penalty_type' => 'required|in:fixed,percent,warning',
            'penalty_value' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $financialPolicy->update($data);

        return redirect()->route('admin.financial-policies.index')
            ->with('success', 'تم تحديث الشريحة المالية بنجاح');
    }

    public function destroy(FinancialPolicy $financialPolicy)
    {
        $financialPolicy->delete();

        return redirect()->route('admin.financial-policies.index')
            ->with('success', 'تم حذف الشريحة المالية بنجاح');
    }
}