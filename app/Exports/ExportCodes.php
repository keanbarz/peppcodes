<?php

namespace App\Exports;

use App\Models\peppcodes;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use \PhpOffice\PhpSpreadsheet\Style\Protection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class ExportCodes implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $field;
    protected $program;

    public function __construct($field, $program,$password)
    {
        $this->field = $field;
        $this->program = $program;
        $this->password = $password;
    }

    public function collection()
    {
        if ($this->field == 'ROXI'){
            $total = doubleval(peppcodes::where('status', 'unclaimed')->where('sender', 'not like', '%' . 'dcfo' . '%')->where('sender', 'not like', '%' . 'dsfo' . '%')->where('sender', 'not like', '%' . 'docfo' . '%')
            ->where('sender', 'not like', '%' . 'dnfo' . '%')->where('sender', 'not like', '%' . 'dieo' . '%')->where('sender', 'not like', '%' . 'dorfo' . '%')
            ->where('sender', 'not like', '%' . 'dofo' . '%')->where('sender', 'like', '%' . $this->program . '%')->sum('principal'));
            log::info($total);
        } else{
            $total = doubleval(peppcodes::where('status', 'unclaimed')->where('sender', 'like', '%' . $this->field . '%')->where('sender', 'like', '%' . $this->program . '%')->sum('principal'));
        }
        $intotal = intval($total);
        

        if  ($intotal == 0){
            throw new Exception();}
            else{ if ($this->field == 'ROXI')
                {
                    $peppcodes = peppcodes::where('status', 'unclaimed')->where('sender', 'not like', '%' . 'dcfo' . '%')->where('sender', 'not like', '%' . 'dsfo' . '%')->where('sender', 'not like', '%' . 'docfo' . '%')
                    ->where('sender', 'not like', '%' . 'dnfo' . '%')->where('sender', 'not like', '%' . 'dieo' . '%')->where('sender', 'not like', '%' . 'dorfo' . '%')
                    ->where('sender', 'not like', '%' . 'dofo' . '%')->where('sender', 'like', '%' . $this->program . '%')->select('tranx_date','status', 'tranx_code', 'sender', 'receiver', 'principal')->get();
                    return $peppcodes->concat([
                        ['NUMBER OF TRANSACTIONS','',count($peppcodes),'TOTAL', '', $total]]);;   
                }
                else {
            $peppcodes = peppcodes::where('status', 'unclaimed')->where('sender', 'like', '%' . $this->field . '%')->where('sender', 'like', '%' . $this->program . '%')->select('tranx_date','status', 'tranx_code', 'sender', 'receiver', 'principal')->get();
            return $peppcodes->concat([
                ['NUMBER OF TRANSACTIONS','',count($peppcodes),'TOTAL', '', $total]
            ]);;}}
    }

    public function headings(): array
    {   
        return ['DATE POSTED', 'STATUS','TRANSACTION CODE', 'SENDER', 'RECEIVER', 'PRINCIPAL'];
    }


    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                // Disable cell editing by protecting the active worksheet
                $sheet = $event->sheet->getDelegate();
            },
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->insertNewRowBefore(1);
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle('F1')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
                $sheet->getProtection()->setSheet(true);
                $sheet->getProtection()->setPassword('123'); // Set a password to protect cells
                $sheet->getProtection()->setSelectLockedCells(true);
                $sheet->getProtection()->setSelectUnlockedCells(false);


                // Adjust column widths to fit content
                $sheet->getColumnDimension('A')->setWidth(13);
                $sheet->getColumnDimension('B')->setWidth(13);
                $sheet->getColumnDimension('C')->setWidth(20);
                $sheet->getColumnDimension('D')->setWidth(45);
                $sheet->getColumnDimension('E')->setWidth(40);
                $sheet->getColumnDimension('F')->setWidth(25);

                $sheet->getStyle('F2:F' . $highestRow)->getNumberFormat()
                ->setFormatCode('#,##0.00');
                $sheet->setCellValue('A1', 'PASSWORD:');
                $sheet->setCellValue('F1', 'Enter password here');
                $sheet->mergeCells('A1:E1');
                $sheet->mergeCells('A' . $highestRow . ':B' . $highestRow);
                $sheet->mergeCells('D' . $highestRow . ':E' . $highestRow);
                $sheet->getStyle('A' . $highestRow . ':F' . $highestRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A1:F2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'], // Black borders
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A1')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    ],
                ]);

                $sheet->getStyle('A2:F' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'], // Black borders
                        ],
                    ],
                    'font' => [
                        'color' => ['argb' => 'FFFFFFFF'],
                    ],
                ]);

                // Create a new conditional formatting rule
                $conditional = new Conditional();
                $conditional->setConditionType(Conditional::CONDITION_EXPRESSION);
                $conditional->setConditions(['$F$1="' . $this->password . '"']); // Value to check
                $conditional->getStyle()->applyFromArray([
                    'font' => [
                        'color' => ['argb' => '000000'],
                    ],
                ]);

                // Apply the conditional formatting to the target cell
                $sheet->getStyle('A2:F' . $highestRow)->setConditionalStyles([$conditional]);

            },

        ];
    }
}
