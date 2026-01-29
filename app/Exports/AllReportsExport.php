<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\Auth;

class AllReportsExport implements WithMultipleSheets
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    private function getReportData($reportMethod)
    {
        $reportsController = app('App\Http\Controllers\ReportsController');

        $response = $reportsController->$reportMethod($this->userId);

        // إذا كان JsonResponse
        $original = $response->getData(true);

        $data = $original['data'] ?? [];

        // إذا كانت Collection
        if ($data instanceof \Illuminate\Support\Collection) {
            return $data->map(fn($row) => (array) $row)->values()->toArray();
        }

        // إذا كانت LengthAwarePaginator أو Paginator
        if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator ||
            $data instanceof \Illuminate\Pagination\Paginator) {
            return $data->items();
        }

        // إذا كانت already array
        if (is_array($data)) {
            return $data;
        }

        // أي نوع آخر
        return [];
    }

    public function sheets(): array
    {
        $roleId = Auth::user()->role_id;
        $sheets = [];

        // === Reports مشتركة ===
        $sheets[] = new DynamicReportExport(
            headers: ['Month', 'Completed Jobs', 'Cancelled Jobs', 'In Progress Jobs', 'Completion Rate'],
            data: $this->getReportData('jobCompletionReport'),
            title: 'Job Completion'
        );

        $sheets[] = new DynamicReportExport(
            headers: ['User Name', 'Satisfaction Rate'],
            data: $this->getReportData('topRatedArtisansReport'),
            title: 'Top Rated'
        );

        // === Earnings فقط للـ role 1 و 2 ===
        if (in_array($roleId, [1, 2])) {
            $sheets[] = new DynamicReportExport(
                headers: ['User Name', 'Completed Jobs', 'Avg Job Price', 'Total Earnings'],
                data: $this->getReportData('earningsReport'),
                title: 'Earnings'
            );
        }

        // === باقي التقارير فقط Super Admin (role 1) ===
        if ($roleId === 1) {

            $sheets[] = new DynamicReportExport(
                headers: ['Month', 'New Users', 'Jobs Posted', 'Completed Jobs', 'Cancelled Jobs', 'In Progress Jobs', 'Completion Rate'],
                data: $this->getReportData('monthlyActivityReport'),
                title: 'Monthly Activity'
            );

            $sheets[] = new DynamicReportExport(
                headers: ['User Name', 'Role', 'Avg Rating', 'Flags', 'Last Reported Issue', 'Action Required'],
                data: $this->getReportData('lowPerformanceUsersReport'),
                title: 'Low Performance'
            );

            $sheets[] = new DynamicReportExport(
                headers: ['City', 'Jobs Posted', 'Top Category', 'Active Artisans', 'Demand/Supply Ratio'],
                data: $this->getReportData('locationBasedDemandReport'),
                title: 'City Demand'
            );

            $sheets[] = new DynamicReportExport(
                headers: ['User Name', 'Completed Jobs', 'Total Earnings'],
                data: $this->getReportData('topJobFinishersReport'),
                title: 'Top Finishers'
            );
        }

        return $sheets;
    }
}
