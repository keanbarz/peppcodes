<?php

namespace App\Imports;
use Carbon\Carbon;
use App\Models\acic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;

class importacic implements ToModel  {
    
    protected $newRecords = [];
    protected $check;
    protected $amount;  

    public function lastcheck()
    {
        return acic::latest('id')->value('check_number');
    }

    public function lastdate()
    {
        return acic::latest('id')->value('check_date');
    }

    public function model(array $row) {
        $date = $row[0] ?? $this->lastdate();
        $this->check = $row[1] ?? $this->lastcheck();
        $this->amount = str_replace(',', '', $row[7]);
        if (strlen($row[7]) < 4 ){
            $ref = $row[7] . '.00'; 
            log::info(strlen($row[7]));
        }
        else {
            $ref = $row[7];
        }


        if ($this->check == $this->lastcheck()) {
            $add = acic::where('check_number',$this->check)->first();
            DB::table('acics')
                    ->where('check_number', $this->check)
                    ->update([
                        'amount'     => str_replace(',', '', strval(number_format($this->amount + $add->amount,2))) ,
                    ]);
                    return null;
        }
        else{
        $this->newRecords[] = [
            'check_date'        => $date,
            'check_number'      => $this->check,
            'payee'             => str_replace("Ñ","N", $row[4]),
            'uacs'              => $row[5],
            'amount'            => str_replace(',', '', $ref),
        ];
        }
        $this->insertBatch();
        return null;
    }

    protected function insertBatch()
    {
        try {
            DB::table('acics')->insert($this->newRecords);
            $this->newRecords = [];
        } catch (\Exception $e) {
            Log::error('Batch insertion error: ' . $e->getMessage());
        }
    }

    public function __destruct()
    {
        if (!empty($this->newRecords)) {
            $this->insertBatch();
        }
    }
}
