<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class requestDetailExport implements FromView, WithStyles
{
    use RegistersEventListeners;
    protected $export;

    public function __construct($export)
    {
        $this->export = $export;
    }

    public function view(): View
    {
        return view('template.exportDetail', ['exportDetail' => $this->export]);
    }

    public function styles(Worksheet $sheet)
    {
        //set border to all cell

        //count $exportDetail['data']
        //auto size column A to H
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);

        $start = 8;
        $count = count($this->export['data']);
        //set alignment left
        $sheet->getStyle('A'.($start-2).':H'.$start-($count-1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);        $sheet->getStyle('A'.$start.':Z' . $start-($count-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A'.$start.':Z' . $count)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->mergeCells('C1:D1');
        $sheet->mergeCells('C2:D2');
        $sheet->mergeCells('C3:D3');
        $sheet->mergeCells('C4:D4');

        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A3')->getFont()->setBold(true);
        $sheet->getStyle('A4')->getFont()->setBold(true);
        //bold row 5
        $sheet->getStyle('A6:Z7')->getFont()->setBold(true);
        //set alignment center on row 5
        $sheet->getStyle('A6:Z6')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A6:Z6')->getAlignment()->setVertical('center');

    }
}
