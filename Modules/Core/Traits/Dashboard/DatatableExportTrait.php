<?php
namespace Modules\Core\Traits\Dashboard;

use Illuminate\Http\Request;
use Modules\Core\Exports\ExportExcel;
use Modules\Core\Traits\DataTable;
use PDF;

trait DatatableExportTrait
{
    use ControllerSetterAndGetter;
    //repository methods

    private $QueryActionsCols;
    protected $exportFileName = 'file';
    /**
     * @param array $data
     */
    public function setQueryActionsCols(array $data = ['id' => 'id'])
    {
        $this->QueryActionsCols = $data;
    }

    /**
     * @return mixed
     */
    public function getQueryActionsCols()
    {
        return $this->QueryActionsCols;
    }

    // Generate PDF
    public function createPDF($data, $type = 'pdf')
    {

        $cols = $this->getQueryActionsCols();
        // share data to view
        //        return View('core::dashboard.query-action.print', compact('cols','data'));
        $pdf = PDF::loadView('core::dashboard.query-action.print', compact('cols', 'data'), [], [
            'default_font' => 'cairo',
            'title' => env('APP_NAME') ?? $this->exportFileName,
            'format' => 'A4-L',
//            'margin_header' => 5,
            'margin_footer' => 5,
            'watermark' => env('APP_NAME') ?? $this->exportFileName,
            'orientation' => 'L',
        ]);

        // download PDF file with download method

        switch ($type) {
            case 'print':
                return $pdf->stream((env('APP_NAME') ?? $this->exportFileName) . '.pdf');
                break;
            default:
                return $pdf->download((env('APP_NAME') ?? $this->exportFileName) . '.pdf');
        }
    }

    // Generate Excel
    public function exportExcel($data, $resource)
    {
        $cols = $this->getQueryActionsCols();

        return (new ExportExcel($data, $cols, $resource))->download(((env('APP_NAME')) ?? $this->exportFileName) . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    // controller methods

    private function getData(Request $request, $type = "data_table")
    {
        if ($type == 'data_table') {
            $datatable = DataTable::drawTable($request, $this->repository->QueryTable($request));
            $resource = $this->model_resource;
        } elseif ($type == 'export') {
            $datatable = DataTable::drawPrint($request, $this->repository->QueryTable($request));
            $resource = $this->export_resource;
        }

        $datatable['data'] = $resource::collection($datatable['data']);

        return Response()->json($datatable);
    }

    public function datatable(Request $request)
    {
        return $this->getData($request);
    }

    public function export(Request $request, $type)
    {
        $data = json_decode($request->data);
        $req = $data->req;
        $request->merge(['req' => (array) $req]);

        switch ($type) {
            case 'pdf':
            case 'print':
                $data = $this->getData($request, 'export');
                return $this->repository->createPDF($data->getData()->data, $type);
                break;
            case 'excel':
                return $this->repository->exportExcel($this->repository->QueryTable($request), $this->export_resource);
                break;
            default:
                return back();
        }
    }
}
