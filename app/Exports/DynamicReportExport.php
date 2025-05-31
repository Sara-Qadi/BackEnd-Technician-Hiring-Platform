<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DynamicReportExport implements FromArray, WithHeadings, WithTitle
{
    protected array $headers;
    protected array $data;
    protected string $title;

    public function __construct(array $headers, array $data, string $title)
    {
        $this->headers = $headers;
        $this->data = $data;
        $this->title = $title;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function title(): string
    {
        return $this->title;
    }
}

