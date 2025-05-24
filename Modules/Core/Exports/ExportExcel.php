<?php

namespace Modules\Core\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportExcel implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    private $data;
    private $cols;
    private $resource;

    public function __construct($data, $cols,$resource)
    {
        $this->data = $data;
        $this->cols = $cols;
        $this->resource = $resource;
    }

    /**
     * @param  mixed  $record
     * @return array
     */
    public function map($record): array
    {
        $resource = $this->resource;
        $data = (new $resource($record))->jsonSerialize();

        $response = [];
        foreach ($this->cols as $col) {
            array_push($response,$data[$col]);
        }

        return $response;
    }

    public function query()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return array_keys($this->cols);
    }
}
