<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerMembership;
use App\Models\MembershipPlan;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reportData = $this->buildReportData($request);

        return view('reports.index', $reportData);
    }

    public function exportExcel(Request $request)
    {
        $reportData = $this->buildReportData($request);

        return Excel::create('reporte_gimnasio_' . now()->format('Y_m_d_His'), function ($excel) use ($reportData) {
            $excel->sheet('Resumen', function ($sheet) use ($reportData) {
                $sheet->fromArray([
                    ['Metrica', 'Valor'],
                    ['Clientes activos', $reportData['activeCustomers']],
                    ['Clientes vencidos', $reportData['expiredCustomers']],
                    ['Membresias proximas a vencer', $reportData['upcomingCustomers']],
                    ['Nuevos en rango', $reportData['newCustomers']],
                    ['Ingresos diarios', $reportData['dailyIncome']],
                    ['Ingresos mensuales', $reportData['monthlyIncome']],
                    ['Ingresos anuales', $reportData['yearlyIncome']],
                ]);
            });

            $excel->sheet('Clientes activos', function ($sheet) use ($reportData) {
                $rows = [['Cliente', 'Documento', 'Telefono']];

                foreach ($reportData['activeCustomersList'] as $customer) {
                    $rows[] = [
                        $customer->first_name . ' ' . $customer->last_name,
                        trim(($customer->documentType?->code ?? '') . ' ' . $customer->document_number),
                        $customer->phone ?? '-',
                    ];
                }

                $sheet->fromArray($rows, null, 'A1', false, false);
            });

            $excel->sheet('Clientes vencidos', function ($sheet) use ($reportData) {
                $rows = [['Cliente', 'Documento', 'Telefono']];

                foreach ($reportData['expiredCustomersList'] as $customer) {
                    $rows[] = [
                        $customer->first_name . ' ' . $customer->last_name,
                        trim(($customer->documentType?->code ?? '') . ' ' . $customer->document_number),
                        $customer->phone ?? '-',
                    ];
                }

                $sheet->fromArray($rows, null, 'A1', false, false);
            });

            $excel->sheet('Membresias', function ($sheet) use ($reportData) {
                $rows = [['Cliente', 'Plan', 'Inicio', 'Vence', 'Estado']];

                foreach ($reportData['upcomingMemberships'] as $membership) {
                    $rows[] = [
                        $membership->customer->first_name . ' ' . $membership->customer->last_name,
                        $membership->plan->name,
                        $membership->start_date?->format('d/m/Y'),
                        $membership->end_date?->format('d/m/Y'),
                        $membership->status,
                    ];
                }

                $sheet->fromArray($rows, null, 'A1', false, false);
            });

            $excel->sheet('Pagos', function ($sheet) use ($reportData) {
                $rows = [['Cliente', 'Fecha', 'Valor', 'Metodo', 'Comprobante']];

                foreach ($reportData['recentPayments'] as $payment) {
                    $rows[] = [
                        $payment->customer->first_name . ' ' . $payment->customer->last_name,
                        $payment->payment_date?->format('d/m/Y'),
                        $payment->amount,
                        $payment->method->name,
                        $payment->receipt_number ?? '-',
                    ];
                }

                $sheet->fromArray($rows, null, 'A1', false, false);
            });
        })->download('xls');
    }

    public function exportPdf(Request $request)
    {
        $reportData = $this->buildReportData($request);

        $pdf = Pdf::loadView('reports.pdf', $reportData)->setPaper('a4', 'portrait');

        return $pdf->download('reporte_gimnasio_' . now()->format('Y_m_d_His') . '.pdf');
    }

    private function buildReportData(Request $request): array
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->input('start_date'))->toDateString()
            : Carbon::now()->startOfMonth()->toDateString();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->input('end_date'))->toDateString()
            : Carbon::now()->endOfMonth()->toDateString();

        $today = Carbon::today();
        $threeDaysFromNow = $today->copy()->addDays(3);

        $activeCustomers = Customer::where('status', 'active')->count();
        $expiredCustomers = Customer::where('status', 'expired')->count();
        $upcomingCustomers = CustomerMembership::where('status', 'active')
            ->whereBetween('end_date', [$today->toDateString(), $threeDaysFromNow->toDateString()])
            ->count();
        $newCustomers = Customer::whereBetween('registered_at', [$startDate, $endDate])->count();

        $dailyIncome = Payment::whereBetween('payment_date', [$startDate, $endDate])->sum('amount');
        $monthlyIncome = Payment::whereMonth('payment_date', Carbon::now()->month)->whereYear('payment_date', Carbon::now()->year)->sum('amount');
        $yearlyIncome = Payment::whereYear('payment_date', Carbon::now()->year)->sum('amount');

        $activeCustomersList = Customer::with('documentType')
            ->where('status', 'active')
            ->latest()
            ->get();

        $expiredCustomersList = Customer::with('documentType')
            ->where('status', 'expired')
            ->latest()
            ->get();

        $upcomingMemberships = CustomerMembership::with('customer.documentType', 'plan')
            ->where('status', 'active')
            ->whereBetween('end_date', [$today->toDateString(), $threeDaysFromNow->toDateString()])
            ->orderBy('end_date')
            ->get();

        $incomeByDay = Payment::selectRaw('DATE(payment_date) as day, SUM(amount) as total')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $incomeByMonth = Payment::selectRaw('DATE_FORMAT(payment_date, "%Y-%m") as period, SUM(amount) as total')
            ->whereYear('payment_date', Carbon::now()->year)
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        $topPlans = MembershipPlan::query()
            ->select('membership_plans.name', DB::raw('COUNT(customer_memberships.id) as total'))
            ->leftJoin('customer_memberships', 'customer_memberships.membership_plan_id', '=', 'membership_plans.id')
            ->groupBy('membership_plans.id', 'membership_plans.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $recentPayments = Payment::with('customer.documentType', 'method', 'user')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->latest('payment_date')
            ->get();

        return compact(
            'startDate',
            'endDate',
            'activeCustomers',
            'expiredCustomers',
            'upcomingCustomers',
            'newCustomers',
            'dailyIncome',
            'monthlyIncome',
            'yearlyIncome',
            'activeCustomersList',
            'expiredCustomersList',
            'upcomingMemberships',
            'incomeByDay',
            'incomeByMonth',
            'topPlans',
            'recentPayments'
        );
    }
}
