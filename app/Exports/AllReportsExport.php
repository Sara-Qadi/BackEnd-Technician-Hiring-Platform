<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AllReportsExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
           /*new DynamicReportExport(
                headers: ['Month', 'Completed Jobs', 'Cancelled Jobs', 'In Progress Jobs', 'Completion Rate'],
                data: app('App\Http\Controllers\ReportsController')->jobCompletionReport()->getData()->data,
                title: 'Job Completion'
            ),*/
            new DynamicReportExport(
                headers: ['User ID', 'User Name', 'Completed Jobs', 'Avg Job Price', 'Total Earnings', 'Job Completion Rate'],
                data: app('App\Http\Controllers\ReportsController')->earningsReport()->getData()->data,
                title: 'Earnings'
            ),
            new DynamicReportExport(
                headers: ['Month', 'New Users', 'Jobs Posted', 'Completed Jobs', 'Cancelled Jobs', 'In Progress Jobs', 'Completion Rate'],
                data: app('App\Http\Controllers\ReportsController')->monthlyActivityReport()->getData()->data,
                title: 'Monthly Activity'
            ),
            new DynamicReportExport(
                headers: ['User ID', 'User Name', 'Category', 'Rating', 'Completed Jobs', 'Satisfaction Rate'],
                data: app('App\Http\Controllers\ReportsController')->topRatedArtisansReport()->getData()->data,
                title: 'Top Rated'
            ),
            new DynamicReportExport(
                headers: ['User Name', 'Role', 'Avg Rating', 'Flags', 'Last Reported Issue', 'Action Required'],
                data: app('App\Http\Controllers\ReportsController')->lowPerformanceUsersReport()->getData()->data,
                title: 'Low Performance'
            ),
            new DynamicReportExport(
                headers: ['City', 'Jobs Posted', 'Top Category', 'Active Artisans', 'Demand/Supply Ratio'],
                data: app('App\Http\Controllers\ReportsController')->locationBasedDemandReport()->getData()->data,
                title: 'City Demand'
            ),
            new DynamicReportExport(
                headers: ['User Name', 'Completed Jobs', 'Total Earnings'],
                data: app('App\Http\Controllers\ReportsController')->topJobFinishersReport()->getData()->data,
                title: 'Top Finishers'
            ),
        ];
    }
}
