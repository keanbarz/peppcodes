<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ExportCodes;
use App\Imports\importcodes;
use App\Imports\importacic;
use App\Models\peppcodes;
use App\Models\acic;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;



class ImportController extends Controller
{
    public function import(Request $request)
    {
        ini_set('max_execution_time', 600); // 5 minutes
        ini_set('memory_limit', '512M');
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        try {
            #demos::truncate(); //demo table, like dev -> prod
        Excel::import(new importcodes, $request->file('file'));
        return redirect()->back()->with('success', 'File imported successfully.');}
        catch (\Exception $e) {
            Log::error('Error importing file: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an error importing the file.');
        }
    }

    public function importacic(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        try {
            acic::truncate();
        Excel::import(new importacic, $request->file('file'));
        acic::first()->delete();
        return redirect()->back()->with('success', 'File imported successfully.');}
        catch (\Exception $e) {
            Log::error('Error importing file: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an error importing the file.');
        }
    }

    public function view(Request $request)
    {
        if (Auth::user()->field_office == 'demo') {
            $lookup = '';
        }
        else {
            $lookup = Auth::user()->field_office;
        }

        $currentYear = date('Y');
        $years = range('2023', $currentYear + 1);

        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subDays(70)->format('m-d-y');
        $endDateRange = $endDate->copy()->subDays(55)->format('m-d-y');
        
        if (Auth::user()->field_office == 'roxi') {
            $forcancel = peppcodes::whereBetween('tranx_date', [$startDate,$endDateRange
            ])->where('sender', 'not like', '%' . 'dcfo' . '%')->where('sender', 'not like', '%' . 'dsfo' . '%')->where('sender', 'not like', '%' . 'docfo' . '%')
            ->where('sender', 'not like', '%' . 'dnfo' . '%')->where('sender', 'not like', '%' . 'dieo' . '%')->where('sender', 'not like', '%' . 'dorfo' . '%')
            ->where('sender', 'not like', '%' . 'dofo' . '%')->where('status', 'unclaimed')->get();
        }
        else
        {$forcancel = peppcodes::whereBetween('tranx_date', [$startDate,$endDateRange
            ])->where('sender', 'like', '%' . $lookup . '%')->where('status',  'unclaimed')->get();}

        return view('peppcodes', ['years' => $years, 'forcancel' => $forcancel]);
    }

    public function acic(Request $request)
    {

        $acics = acic::all();
        return view('acic', ['acics' => $acics]);
    }


    public function dashboard(Request $request)
    {
        $peppsum = peppcodes::query()->where('sender', 'like', '%' . Auth::user()->field_office . '%');
        //$gip = $summary->where('')

        return view('dashboard', compact('peppsum'));
    }

    public function filter(Request $request)
    {
        //admin & demo purposes
        if (Auth::user()->field_office == 'demo') {
            $lookup = '';
        }
        else {
            $lookup = Auth::user()->field_office;
        }


        //roxi exclusive
        if (Auth::user()->field_office == 'roxi' || $request->field == 'roxi') {
            $search = peppcodes::query()->where('sender', 'not like', '%' . 'dcfo' . '%')->where('sender', 'not like', '%' . 'dsfo' . '%')->where('sender', 'not like', '%' . 'docfo' . '%')
            ->where('sender', 'not like', '%' . 'dnfo' . '%')->where('sender', 'not like', '%' . 'dieo' . '%')->where('sender', 'not like', '%' . 'dorfo' . '%')
            ->where('sender', 'not like', '%' . 'dofo' . '%')->where('tranx_date', 'like', '%' . $request->year . '%')->where('status', 'like', $request->status . '%');
            $searchf = $search->where('sender', 'like', '%' . $request->program . '%');
            $results = $searchf->where('receiver', 'like', '%' . $request->search . '%')->paginate(15);
        }
        //admin&demo exclusive - non functional (FINAL TESTING - FOR REMOVAL)
        /*elseif (Auth::user()->field_office != 'roxi' && $request->field == 'roxi') {
            $search = peppcodes::query()->where('sender', 'not like', '%' . 'dcfo' . '%')->where('sender', 'not like', '%' . 'dsfo' . '%')->where('sender', 'not like', '%' . 'docfo' . '%')
            ->where('sender', 'not like', '%' . 'dnfo' . '%')->where('sender', 'not like', '%' . 'dieo' . '%')->where('sender', 'not like', '%' . 'dorfo' . '%')
            ->where('sender', 'not like', '%' . 'dofo' . '%')->where('tranx_date', 'like', '%' . $request->year . '%')->where('status', 'like', '%' . $request->status . '%');
            $results = $search->where('receiver', 'like', '%' . $request->search . '%')->paginate(15);
        }*/
        //any other field office
        else
        {$search = peppcodes::query()->where('sender', 'like', '%' . $lookup . '%')->where('tranx_date', 'like', '%' . $request->year . '%')
            ->where('status', 'like', $request->status . '%');
            $searchf = $search->where('sender', 'like', '%' . $request->program . '%');
        $results = $searchf->where('receiver', 'like', '%' . $request->search . '%')->where('sender', 'like', '%' . $request->field . '%')->paginate(15);}

        $total = $search->sum('principal');
        /*->orWhere('tranx_code', 'like', '%' . $request->search . '%')
        /*->orWhere('sender', 'like', '%' . $request->search . '%')*/
        

        return response()->json([
            'results' => $results->items(),
            'pagination' => (string) $results->appends($request->except('page'))->links()
        ]);
    }

    public function generatePDF(Request $request)
    {

        if (Auth::user()->field_office == 'demo') {
            $lookup = '';
        }
        else {
            $lookup = Auth::user()->field_office;
        }
        
        if (Auth::user()->field_office == 'roxi') {
            $peppcodes = peppcodes::query()->where('sender', 'not like', '%' . 'dcfo' . '%')->where('sender', 'not like', '%' . 'dsfo' . '%')
            ->where('sender', 'not like', '%' . 'docfo' . '%')
            ->where('sender', 'not like', '%' . 'dnfo' . '%')->where('sender', 'not like', '%' . 'dieo' . '%')->where('sender', 'not like', '%' . 'dorfo' . '%')
            ->where('sender', 'not like', '%' . 'dofo' . '%')->where('tranx_date', 'like', '%' . $request->year . '%')
        ->where('status',  'like', $request->status . '%' )->where('sender', 'like', '%' . $request->field . '%')->where('sender', 'like', '%' . $request->program . '%')->get();
        }
        else
        {$peppcodes = peppcodes::query()->where('sender', 'like', '%' . $lookup . '%')->where('tranx_date', 'like', '%' . $request->year . '%')
        ->where('status',  'like', $request->status . '%' )->where('sender', 'like', '%' . $request->field . '%')->where('sender', 'like', '%' . $request->program . '%')->get();}
        $status = ucwords($request->status);
        $year = $request->year;
        Log::info($peppcodes);
        $pages = $peppcodes->chunk(10);
        $totalPages = $pages->count();
        $sum = $peppcodes->sum('principal');
        $count = $peppcodes->count();

        if ($request->program != '') {
            $program = strtoupper($request->program);
        }
        else {
            $program = '';
        }
       
        if ($request->field != '') {
            $field = strtoupper($request->field);
        }
        else {
            $field = '';
        }   
        

        $data = [ 'peppcodes' => $peppcodes,
                  'count'     => $count,
                  'year'      => $year,
                  'status'    => $status,
                  'pages'     => $pages,
                  'sum'       => $sum,
                  'field'     => $field,
                  'program'   => $program,
                  'totalPages'=> $totalPages,
                   ];
        if ($peppcodes->isEmpty()) {
            return response()->json(['error' => 'Controller'], 400);
        }
        else {
        $pdf = PDF::loadView('pdf.pdf', $data); 
        return $pdf->stream('example.pdf');
        }
    }

    public function acicdel()
    {
        acic::truncate();
        return redirect()->back();
    }

    public function acicPDF(Request $request)
    {   $acics = acic::all();
        $presum = $acics->sum('amount');
        $sum = $presum;
        $inwords = strtoupper($this->spellNumber($presum));
        $acct = '2016903259'; #str_replace("-", "", $account_number);
        $acicpad = str_pad(str_replace("-","",$request->input('acicno')),10, '0', STR_PAD_LEFT);
        $ncapad = str_pad(str_replace("-","",$request->input('nca')),10,'0', STR_PAD_LEFT);
        $hash_total = 0;
        $hash = 0;

        if ($request->input('request') == 'pdf') {
        
            $presum = substr(round(($presum-floor($presum)),2),2);

            if (substr($presum,0,1) == 0) {
                $presum = substr($presum,1);
            }

            if ($presum == 1) {
                $inwords .= ' PESOS AND ' . strtoupper($this->spellNumber($presum)) . ' CENTAVO';
            }
            elseif ($presum > 1) {
                $inwords .= ' PESOS AND ' . strtoupper($this->spellNumber($presum)) . ' CENTAVOS';
            }
            else {
                $inwords .= ' PESOS';
            }

            $str="1523412453";
            $num1 = (substr($acct, 6 , 1));
            $num2 = (substr($acicpad, 5 , 1));
            $num3 = (substr($ncapad, 8 , 1));

            foreach($acics as $acic) {
                $checkpad = str_pad($acic->check_number,10,'0', STR_PAD_LEFT);
                $num4 = (substr($checkpad, 7 , 1));
                for ($startIndex = 0; $startIndex <= 9; $startIndex++){
                    $hash += (intval(substr($acct, $startIndex, 1)) + intval(substr($acicpad, $startIndex, 1)) + intval(substr($ncapad, $startIndex, 1)) + intval(substr($checkpad, $startIndex, 1))) * intval(substr($str, $startIndex, 1));
                }
                if ($num1 == 0){
                    $num1 = 1;
                };
                if ($num2 == 0){
                    $num2 = 1;
                };
                if ($num3 == 0){
                    $num3 = 1;
                };
                if ($num4 == 0){
                    $num4 = 1;
                };
                $hash_total += ($hash * $num1 * $num2 * $num3 * $num4 * $acic->amount);
                //Reset individual hash
                $hash = 0;}

            $data = [ 'acics'           => $acics,
                    'acicno'          => $request->input('acicno'),
                    'nca'             => $request->input('nca'),
                    'hash_total'      => $hash_total,
                    'sum'             => $sum,
                    'inwords'        => $inwords,
                    ];
            if ($acics->isEmpty()) {
                return response()->json(['error' => 'Controller'], 400);
            }
            else {
            $pdf = PDF::loadView('pdf.acicpdf', $data); 
            return $pdf->stream('example.pdf');
            }
        }
        elseif ($request->input('request') == 'txt')
        {
            // Define the file name
            $fileName = "DOLE". str_replace("-","",$request->input('acicno')) . ".txt";

            // Open the file for writing
            $file = fopen($fileName, "w");

            // Add data rows
            foreach ($acics as $row) {
                $date = explode('/', $row->check_date);


                $line = $acct . str_pad($row->check_number,10,'0', STR_PAD_LEFT) . str_replace("-","",$request->input('acicno')) . str_pad(str_replace("-","",$request->input('nca')),7,'0', STR_PAD_LEFT)
                 . "****" . $date[2] . str_pad($date[0], 2, '0', STR_PAD_LEFT) . str_pad($date[1], 2, '0', STR_PAD_LEFT) . str_pad(str_replace(".","",$row->amount),15,'0', STR_PAD_LEFT) 
                 . substr($row->payee,0,40) . str_repeat(" ", 40-(strlen(substr($row->payee,0,40)))). $row->uacs . "  ";
                fwrite($file, $line);
            }

            // Close the file
            fclose($file);

            // Provide file as a download
            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            readfile($fileName);

            // Optionally delete the file from server after download
            unlink($fileName);

        }
    }

    public function notify(Request $request)
    {
        return view('notify');
    }

    public function export()
    {
        //Cleanse Directory
        $directory = 'public/palawan';
        if (Storage::exists($directory)) {
            Storage::deleteDirectory($directory);}

        //return Excel::download(new ExportCodes, 'codes.xlsx');
        $fields = ['DCFO', 'ROXI', 'DSFO', 'DOCFO', 'DNFO', 'DIEO', 'DORFO', 'DOFO'];

        foreach ($fields as $field){
            $programs = ['GIP', 'TUPAD', 'SPES'];
            foreach ($programs as $program) {
                try {
                    Excel::store(new ExportCodes($field,$program), 'palawan/' . $field . '/' . $program . '/' . $field . '_' . $program . '.xlsx','public');
                } catch (\Exception $e) {}
            }
        }
        return redirect()->back()->with('success', 'File imported successfully.');
        /*Excel::store(new ExportCodes, 'codes.xlsx','public/palawan');
        return redirect()->back()->with('success', 'File imported successfully.');*/
    }


    #Helper Functions

    function spellNumber($presum) 
    {
        $words = [
            0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen',
            20 => 'twenty', 30 => 'thirty', 40 => 'forty', 50 => 'fifty', 60 => 'sixty', 70 => 'seventy', 80 => 'eighty', 90 => 'ninety'
        ];
    
        if ($presum < 20) {
            return $words[$presum];
        }
    
        if ($presum < 100) {
            return $words[(($presum / 10) * 10)-($presum % 10)] . '-' . $this->spellNumber($presum % 10);
        }
    
        if ($presum < 1000) {
            return $words[$presum / 100] . ' hundred ' . $this->spellNumber($presum % 100);
        }
    
        if ($presum < 1000000) {
            return $this->spellNumber($presum / 1000) . ' thousand ' . $this->spellNumber($presum % 1000);
        }

        if ($presum < 1000000000) {
            return $this->spellNumber($presum / 1000000) . ' million ' . $this->spellNumber($presum % 1000000);
        }    

        return 'Number out of range';
    }


}

/*For addition

Artisan::call('email:process');*/