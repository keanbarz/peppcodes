<?php

namespace App\Imports;

use App\Models\peppcodes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class importcodes implements ToCollection, WithChunkReading
{
    protected $existingCodes = [];
    protected $newRecords = [];
    protected $updateRecords = [];
    protected $batchSize = 500;
    protected $count = 0;

    public function __construct()
    {
        // Preload existing transaction codes into a map for fast lookups
        $this->existingCodes = peppcodes::select('id', 'tranx_code', 'status')
            ->get()
            ->keyBy('tranx_code')
            ->toArray();
    }

    public function collection(Collection $rows)
    {
        // Skip the first row and last 2 rows
        $rows = $rows->slice(1, $rows->count() - 3);

        foreach ($rows as $row) {
            $this->count++;
            $stat = $this->determineStatus($row[1]);
            $tranxCode = substr($row[2], 0, 13);
            $existingRecord = $this->findExistingRecord($tranxCode);

            if ($existingRecord) {
                if ($existingRecord['status'] != $stat || $existingRecord['tranx_code'] != $row[2]) {
                    Log::info(sprintf("%02d", $this->count) . '. Updating status or code.');
                    $this->updateRecords[] = [
                        'id'         => $existingRecord['id'],
                        'status'     => $stat,
                        'tranx_code' => $row[2],
                        'receiver'   => $row[4],
                    ];
                } else {
                    Log::info(sprintf("%02d", $this->count) . '. No changes are being made.');
                }

                if (count($this->updateRecords) >= $this->batchSize) {
                    $this->updateBatch();
                }
            } else {
                Log::info(sprintf("%02d", $this->count) . '. Inserting new entry into the database.');
                $this->newRecords[] = [
                    'tranx_date' => Carbon::createFromTimestamp((intval($row[0]) - 25569) * 86400)->format('m-d-Y'),
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
        }

        // Final insert/update
        $this->insertBatch();
        $this->updateBatch();
    }

    protected function findExistingRecord($tranxCode)
    {
        foreach ($this->existingCodes as $code => $record) {
            if (strpos($code, $tranxCode) === 0) {
                return $record;
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
        if (empty($this->newRecords)) return;

        try {
            DB::table('peppcodes')->insert($this->newRecords);
            $this->newRecords = [];
        } catch (\Exception $e) {
            Log::error('Batch insertion error: ' . $e->getMessage());
        }
    }

    protected function updateBatch()
    {
        if (empty($this->updateRecords)) return;

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

    public function chunkSize(): int
    {
        return 1000;
    }
}
