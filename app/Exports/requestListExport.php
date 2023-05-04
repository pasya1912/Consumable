<?php

namespace App\Exports;

use App\Models\Requests as Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;




class requestListExport implements WithHeadings, WithStyles
{
    use RegistersEventListeners;

    private $from, $to;
    public function __construct($from = null, $to = null)
    {
        $this->from = $from;
        $this->to = $to;

    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Request::join('jadwal', 'request.id_jam', 'jadwal.id')->join('users', 'request.user', 'users.username')
        ->select('users.nama as nama_department', 'request.nama as nama_pengambil','request.tanggal', DB::raw('concat(jadwal.awal,"-",jadwal.akhir) as jam_pengambilan'), 'request.status', 'request.admin_note');
        if ($this->from != null) {
            $query = $query->whereDate('request.tanggal', '>=', $this->from);
        }
        if ($this->to != null) {
            $query = $query->whereDate('request.tanggal', '<=', $this->to);
        }
        //add column namein first row
        return $query->get();
    }
    public function headings(): array
    {
        return [
            'Department',
            'Pengambil',
            'Tanggal Ambil',
            'Jadwal Ambil',
            'Status',
            'Notes'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Apply bold font to the first row
        $sheet->getStyle('1')->getFont()->setBold(true);
    }
    public static function afterSheet(AfterSheet $event)
    {
        // Register the event listener to apply styles to the sheet
        $event->sheet->getStyle('1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);
    }
}
