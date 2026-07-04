<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Customer;
use App\Models\CustomerMembership;
use App\Models\MembershipPlan;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();
        $threeDaysFromNow = Carbon::today()->addDays(3);

        $activeCustomers = Customer::where('status', 'active')->count();
        $expiredCustomers = Customer::where('status', 'expired')->count();
        $activeMemberships = CustomerMembership::where('status', 'active')->count();
        $expiredMemberships = CustomerMembership::where('status', 'expired')->count();
        $expiringToday = CustomerMembership::whereDate('end_date', $today)->where('status', 'active')->count();
        $expiringIn3Days = CustomerMembership::whereBetween('end_date', [$today->toDateString(), $threeDaysFromNow->toDateString()])
            ->where('status', 'active')
            ->count();
        $newCustomersMonth = Customer::whereBetween('registered_at', [$monthStart, $monthEnd])->count();

        $incomeToday = Payment::whereDate('payment_date', $today)->sum('amount');
        $incomeMonth = Payment::whereBetween('payment_date', [$monthStart, $monthEnd])->sum('amount');
        $paymentsMonth = Payment::whereBetween('payment_date', [$monthStart, $monthEnd])->count();
        $expectedCash = CustomerMembership::where('status', 'active')->sum('paid_amount');
        $paymentsToday = Payment::whereDate('payment_date', $today)->count();

        $incomeByDay = Payment::selectRaw('DATE(payment_date) as day, SUM(amount) as total')
            ->whereBetween('payment_date', [$monthStart, $monthEnd])
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $newCustomersByMonth = Customer::selectRaw('DATE_FORMAT(registered_at, "%Y-%m") as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $incomeChartLabels = $incomeByDay->pluck('day')->map(fn($day) => Carbon::parse($day)->format('d/m'))->values();
        $incomeChartValues = $incomeByDay->pluck('total')->values();
        $newCustomerChartLabels = $newCustomersByMonth->pluck('month')->values();
        $newCustomerChartValues = $newCustomersByMonth->pluck('total')->values();

        $topMembershipPlans = MembershipPlan::query()
            ->select('membership_plans.name', DB::raw('COUNT(customer_memberships.id) as total'))
            ->leftJoin('customer_memberships', 'customer_memberships.membership_plan_id', '=', 'membership_plans.id')
            ->groupBy('membership_plans.id', 'membership_plans.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $alerts = Alert::query()
            ->with('customer', 'membership')
            ->where('is_read', false)
            ->orderByDesc('generated_at')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'activeCustomers',
            'expiredCustomers',
            'activeMemberships',
            'expiredMemberships',
            'expiringToday',
            'expiringIn3Days',
            'newCustomersMonth',
            'incomeToday',
            'incomeMonth',
            'expectedCash',
            'paymentsToday',
            'paymentsMonth',
            'incomeByDay',
            'newCustomersByMonth',
            'incomeChartLabels',
            'incomeChartValues',
            'newCustomerChartLabels',
            'newCustomerChartValues',
            'topMembershipPlans',
            'alerts'
        ));
    }
}
