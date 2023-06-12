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
use Maatwebsite\Excel\Concerns\WithColumnWidths;



class requestListExport implements WithHeadings, WithStyles, FromCollection,WithColumnWidths
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
        $query = Request::select('users.nama as department','request.nama', 'request.tanggal', DB::raw('concat(jadwal.awal,":",jadwal.akhir) as jam_pengambilan'), 'request.status', 'request.admin_note')
            ->leftJoin('jadwal', 'request.id_jam', '=', 'jadwal.id')
            ->leftJoin('users', 'request.user', '=', 'users.username');
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
    public function columnWidths(): array
    {
        return [
            'A' => 35,
            'B' => 35,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 50,
        ];
    }
}
