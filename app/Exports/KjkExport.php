<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KjkExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected Collection $data;
    protected Collection $bulanList;
    protected int $tahun;
    protected int $no = 0;

    public function __construct(Collection $data, Collection $bulanList, int $tahun)
    {
        $this->data = $data;
        $this->bulanList = $bulanList;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return array_merge(
            ['No', 'NIP', 'Nama'],
            $this->bulanList
                ->map(fn($nama) => strtoupper(substr($nama, 0, 3)))
                ->values()
                ->toArray(),
            ['Total Tahun']
        );
    }

    public function map($item): array
    {
        $this->no++;

        $row = [$this->no, $item['nip'], $item['nama']];

        foreach ($this->bulanList->keys() as $kodeBulan) {
            $row[] = $item['per_bulan'][$kodeBulan];
        }

        $row[] = $item['total_tahun'];

        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        // Header: biru gelap, teks putih, bold
        $sheet->getStyle('A1:P1')->applyFromArray([
            'font'    => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'    => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '25396F'],
            ],
            'alignment' => ['horizontal' => 'center'],
        ]);

        return [];
    }

    public function title(): string
    {
        return 'KJK ' . $this->tahun;
    }
}
