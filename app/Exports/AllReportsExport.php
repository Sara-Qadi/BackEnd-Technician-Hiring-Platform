<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
class AllReportsExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
           new DynamicReportExport(
                headers: ['Month', 'Completed Jobs', 'Cancelled Jobs', 'In Progress Jobs', 'Completion Rate'],
               data: json_decode(json_encode(app('App\Http\Controllers\ReportsController')->jobCompletionReport()->getData()->data), true),
                title: 'Job Completion'
            ),
            new DynamicReportExport(
                headers: ['User ID', 'User Name', 'Completed Jobs', 'Avg Job Price', 'Total Earnings', 'Job Completion Rate'],
                data: json_decode(json_encode(app('App\Http\Controllers\ReportsController')->earningsReport()->getData()->data), true),

                title: 'Earnings'
            ),
            new DynamicReportExport(
                headers: ['Month', 'New Users', 'Jobs Posted', 'Completed Jobs', 'Cancelled Jobs', 'In Progress Jobs', 'Completion Rate'],
                 data: json_decode(json_encode(app('App\Http\Controllers\ReportsController')->monthlyActivityReport()->getData()->data), true),
                title: 'Monthly Activity'
            ),
            new DynamicReportExport(
                headers: ['User ID', 'User Name', 'Category', 'Rating', 'Completed Jobs', 'Satisfaction Rate'],
                 data: json_decode(json_encode(app('App\Http\Controllers\ReportsController')->topRatedArtisansReport()->getData()->data), true),
                title: 'Top Rated'
            ),
            new DynamicReportExport(
                headers: ['User Name', 'Role', 'Avg Rating', 'Flags', 'Last Reported Issue', 'Action Required'],
                 data: json_decode(json_encode(app('App\Http\Controllers\ReportsController')->lowPerformanceUsersReport()->getData()->data), true),
                title: 'Low Performance'
            ),
            new DynamicReportExport(
                headers: ['City', 'Jobs Posted', 'Top Category', 'Active Artisans', 'Demand/Supply Ratio'],
                 data: json_decode(json_encode(app('App\Http\Controllers\ReportsController')->locationBasedDemandReport()->getData()->data), true),
                title: 'City Demand'
            ),
            new DynamicReportExport(
                headers: ['User Name', 'Completed Jobs', 'Total Earnings'],
                 data: json_decode(json_encode(app('App\Http\Controllers\ReportsController')->topJobFinishersReport()->getData()->data), true),
                title: 'Top Finishers'
            ),
        ];
    }
}
