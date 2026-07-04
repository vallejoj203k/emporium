<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMembershipPlanRequest;
use App\Http\Requests\UpdateMembershipPlanRequest;
use App\Models\MembershipPlan;

class MembershipPlanController extends Controller
{
    public function index()
    {
        $plans = MembershipPlan::latest()->paginate(10);

        return view('membership-plans.index', compact('plans'));
    }

    public function create()
    {
        return view('membership-plans.create');
    }

    public function store(StoreMembershipPlanRequest $request)
    {
        MembershipPlan::create($request->validated());

        return redirect()->route('membership-plans.index')->with('success', 'Membresía creada correctamente.');
    }

    public function edit(MembershipPlan $membership_plan)
    {
        return view('membership-plans.edit', ['plan' => $membership_plan]);
    }

    public function update(UpdateMembershipPlanRequest $request, MembershipPlan $membership_plan)
    {
        $membership_plan->update($request->validated());

        return redirect()->route('membership-plans.index')->with('success', 'Membresía actualizada correctamente.');
    }

    public function destroy(MembershipPlan $membership_plan)
    {
        $membership_plan->update(['is_active' => false]);

        return redirect()->route('membership-plans.index')->with('success', 'Membresía desactivada correctamente.');
    }
}
