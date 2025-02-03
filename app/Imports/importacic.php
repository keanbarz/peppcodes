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

    public function merge()
    {
        if ($this->check == $this->lastcheck()) {
            $add = acic::where('check_number',$this->check)->first();
            log::info($add->amount);
            DB::table('acics')
                    ->where('check_number', $this->check)
                    ->update([
                        'amount'     => $this->amount + $add->amount ,
                    ]);
                    return null;
        }
        return null;
    }

    public function model(array $row) {
        $date = $row[0] ?? $this->lastdate();
        $this->check = $row[1] ?? $this->lastcheck();
        $this->amount = str_replace(',', '', $row[7]);

        if ($this->check == $this->lastcheck()) {
            $add = acic::where('check_number',$this->check)->first();
            log::info($add->amount);
            DB::table('acics')
                    ->where('check_number', $this->check)
                    ->update([
                        'amount'     => $this->amount + $add->amount ,
                    ]);
                    return null;
        }
        else{
        $this->newRecords[] = [
            'check_date'        => $date,
            'check_number'      => $this->check,
            'payee'             => str_replace("Ñ","N", $row[4]),
            'uacs'              => $row[5],
            'amount'            => str_replace(',', '', $row[7]),
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
