<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class DynamicReportExport implements FromArray, WithTitle
{
    protected array $data;
    protected array $headers;
    protected string $title;

    public function __construct(array $headers, array $data, string $title)
    {
        $this->headers = $headers;
        $this->data = $data;
        $this->title = $title;
    }

    public function array(): array
    {
        return [$this->headers, ...$this->data];
    }

    public function title(): string
    {
        return $this->title;
    }
}
