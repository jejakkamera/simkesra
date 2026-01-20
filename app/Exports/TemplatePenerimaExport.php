<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class TemplatePenerimaExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    public function collection(): Collection
    {
        // Sample data row to show expected format
        return collect([
            [
                'nik' => '3215012345678901',
                'nama_lengkap' => 'Ahmad Suryadi',
                'tempat_lahir' => 'Karawang',
                'tanggal_bulan_tahun_lahir' => '15/08/1985',
                'alamat' => 'Jl. Merdeka No. 123',
                'rt' => '001',
                'rw' => '002',
                'keldesa' => 'Tanjungpura',
                'kecamatan' => 'Karawang Barat',
                'kode_pos' => '41311',
                'nama_ibu_kandung' => 'Siti Aminah',
                'tempat_mengajar' => 'SDN 1 Karawang',
                'alamat_lembaga' => 'Jl. Sudirman No. 45',
                'no_rekening' => '1234567890',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'nik',
            'nama_lengkap',
            'tempat_lahir',
            'tanggal_bulan_tahun_lahir',
            'alamat',
            'rt',
            'rw',
            'keldesa',
            'kecamatan',
            'kode_pos',
            'nama_ibu_kandung',
            'tempat_mengajar',
            'alamat_lembaga',
            'no_rekening',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold (headings)
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '000000'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFFF00'],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Ensure the header row is visible
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(20);
            },
        ];
    }
}
