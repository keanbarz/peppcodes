<?php

namespace App\Imports;

use App\Models\peppcodes;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;

class importcodes implements ToModel, WithChunkReading {
    protected $existingCodes = [];
    protected $newRecords = [];
    protected $updateRecords = [];
    protected $batchSize = 500;
    protected $count = 0;


    public function __construct() {
        // Preload existing transaction codes into a map for fast lookups
        $this->existingCodes = peppcodes::select('id', 'tranx_code', 'status')
            ->get()
            ->keyBy('tranx_code')
            ->toArray();

    }

    public function model(array $row) {
        $this->count++;
        $stat = $this->determineStatus($row[1]);
        $tranxCode = substr($row[2], 0, 13);

        // Find existing record using preloaded data
        $existingRecord = $this->findExistingRecord($tranxCode);
        
        if ($existingRecord) {
            // Update if the status has changed
            if ($existingRecord['status'] != $stat) {
                log::info(sprintf("%02d", $this->count) . '. Changing status from ' . $existingRecord['status'] . ' to ' . $stat . '.');
                $this->updateRecords[] = [
                    'id'         => $existingRecord['id'],
                    'status'     => $stat,
                    'tranx_code' => $row[2],
                    'receiver'   => $row[4],
                ];
                if (count($this->updateRecords) >= $this->batchSize) {
                    $this->updateBatch();   
                }
            }
            else {
                log::info(sprintf("%02d", $this->count) . '. No changes are being made.');
            }
        } 
        else {
            // Collect new records for batch insertion
            log::info(sprintf("%02d", $this->count) . '. inserting new enrty into the database.');
            $this->newRecords[] = [
                'tranx_date' => Carbon::createFromTimestamp((intval($row[0]) - 25569) * 86400)->format('Y-m-d'),
                'status'     => $stat,
                'tranx_code' => $row[2],
                'sender'     => $row[3],
                'receiver'   => $row[4],
                'principal'  => $row[5],
                'fee'        => $row[6],
                'total'      => $row[7],
            ];

            if (count($this->newRecords) >= $this->batchSize) {
                $this->insertBatch();
            }
        }
        $this->insertBatch();
        return null;
    }

    protected function findExistingRecord($tranxCode) {
        foreach ($this->existingCodes as $code => $record) {
            // Check if the preloaded code starts with the given $tranxCode
            if (strpos($code, $tranxCode) === 0) {
                return $record; // Return the matching record
            }
        }
        return null;
    }

    protected function determineStatus($status)
    {
        return match ($status) {
            'xx' => 'Cancelled',
            '**' => 'Claimed',
            default => 'Unclaimed',
        };
    }

    protected function insertBatch()
    {
        try {
            DB::table('peppcodes')->insert($this->newRecords);
            $this->newRecords = [];
        } catch (\Exception $e) {
            Log::error('Batch insertion error: ' . $e->getMessage());
        }
    }

    protected function updateBatch()
    {
        try {
            $updates = collect($this->updateRecords);
            $updates->groupBy('id')->each(function ($group, $id) {
                $record = $group->first();
                DB::table('peppcodes')
                    ->where('id', $id)
                    ->update([
                        'status'     => $record['status'],
                        'tranx_code' => $record['tranx_code'],
                        'receiver'   => $record['receiver'],
                    ]);
            });
            $this->updateRecords = [];
        } catch (\Exception $e) {
            Log::error('Batch update error: ' . $e->getMessage());
        }
    }

    public function __destruct()
    {
        if (!empty($this->newRecords)) {
            $this->insertBatch();
        }

        if (!empty($this->updateRecords)) {
            $this->updateBatch();
        }
    }

    public function chunkSize(): int
    {
        return 1000; // Process 1000 rows at a time
    }
}
