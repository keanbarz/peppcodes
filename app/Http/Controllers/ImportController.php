<?php

namespace App\Http\Controllers;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Exports\ExportCodes;
use App\Imports\importcodes;
use App\Models\peppcodes;
use Carbon\Carbon;
use ZipArchive;




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
            Excel::import(new importcodes, $request->file('file'));
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
        else{
            $forcancel = peppcodes::whereBetween('tranx_date', [$startDate,$endDateRange
            ])->where('sender', 'like', '%' . $lookup . '%')->where('status',  'unclaimed')->get();
        }

        return view('peppcodes', ['years' => $years, 'forcancel' => $forcancel]);
    }

    public function dashboard(Request $request)
    {
        $currentYear = date('Y');
        $years = range('2023', $currentYear + 1);
        $fv = $request->input('field');
        $cy = $request->input('year');
        if (Auth::user()->field_office == 'demo' || Auth::user()->field_office == '') {
            if ($request->input('field') == ''){
                $lookup = '';
            }
            else {
                $lookup = $request->input('field');
            }
        }
        else {
            $lookup = Auth::user()->field_office;
        }

        if ($fv == 'roxi') {
            $peppsum = peppcodes::where('sender', 'not like', '%' . 'dcfo' . '%')->where('sender', 'not like', '%' . 'dsfo' . '%')->where('sender', 'not like', '%' . 'docfo' . '%')
                ->where('sender', 'not like', '%' . 'dnfo' . '%')->where('sender', 'not like', '%' . 'dieo' . '%')->where('sender', 'not like', '%' . 'dorfo' . '%')
                ->where('sender', 'not like', '%' . 'dofo' . '%')->where('tranx_date', 'like', '%' . $request->year . '%')->get();
        }
        else{
            $peppsum = peppcodes::where('sender', 'like', '%' . $lookup . '%')->where('tranx_date', 'like', '%' . $request->year . '%')->get();
        }
        return view('dashboard', compact('peppsum','years','fv','cy'));
    }

    public function filter(Request $request)
    {
        //admin & demo purposes (might delete demo table)
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
        //any other field office
        else
        {$search = peppcodes::query()->where('sender', 'like', '%' . $lookup . '%')->where('tranx_date', 'like', '%' . $request->year . '%')
            ->where('status', 'like', $request->status . '%');
            $searchf = $search->where('sender', 'like', '%' . $request->program . '%');
        $results = $searchf->where('receiver', 'like', '%' . $request->search . '%')->where('sender', 'like', '%' . $request->field . '%')->paginate(15);}

        $total = $search->sum('principal');
        /*->orWhere('tranx_code', 'like', '%' . $request->search . '%')
        /*->orWhere('sender', 'like', '%' . $request->search . '%')*/
        /* ->where('sender', 'like', '%' . $request->field . '%') */
        

        return response()->json([
            'results' => $results->items(),
            'pagination' => (string) $results->appends($request->except('page'))->links()
        ]);
    }

    public function generatePDF(Request $request)
    {
        ini_set('max_execution_time', 600); // 5 minutes
        ini_set('memory_limit', '2048M');

        $field = $request->field ?? '';
        $program = $request->program ?? '';
        $status = $request->status ?? '';
        $year = $request->year ?? '';

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
            ->where('sender', 'not like', '%' . 'dofo' . '%')->where('tranx_date', 'like', '%' . $year . '%')
        ->where('status',  'like', $status . '%' )->where('sender', 'like', '%' . $field . '%')->where('sender', 'like', '%' . $program . '%')->get();
        }
        else
        {$peppcodes = peppcodes::query()->where('sender', 'like', '%' . $lookup . '%')->where('tranx_date', 'like', '%' . $year . '%')
        ->where('status',  'like', $status . '%' )->where('sender', 'like', '%' . $field . '%')->where('sender', 'like', '%' . $program . '%')->get();}
        $status = ucwords($status);
        $year = $year;
        $pages = $peppcodes->chunk(12);
        $totalPages = $pages->count();
        $sum = $peppcodes->sum('principal');
        $count = $peppcodes->count();

        if ($program != '') {
            $program = strtoupper($program);
        }
        else {
            $program = '';
        }
       
        if ($field != '') {
            $field = strtoupper($field);
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
        return $pdf->stream('report.pdf');
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

        $sevenZipPath = 'C:\\Program Files\\7-Zip\\7z.exe';

        //return Excel::download(new ExportCodes, 'codes.xlsx');
        $fields = ['DCFO', 'ROXI', 'DSFO', 'DOCFO', 'DNFO', 'DIEO', 'DORFO', 'DOFO'];
        $programs = ['GIP', 'TUPAD', 'SPES'];

        foreach ($fields as $field){
            foreach ($programs as $program) {
                try {
                    
                    $password = $program[0] . 'dole11' . strtolower($field) . date('mdy');
                    $relativeTempPath = 'palawan/' . $field . '/' . $program . '/' . $field . '_' . $program . '.xlsx';
                    $absoluteTempPath = storage_path('app/public/' . $relativeTempPath);

                    Excel::store(new ExportCodes($field,$program,$password), $relativeTempPath,'public');

                    $spreadsheet = IOFactory::load($absoluteTempPath);
                    $spreadsheet->getSecurity()->setLockWindows(true);
                    $spreadsheet->getSecurity()->setLockStructure(true);
                    $spreadsheet->getSecurity()->setLockRevision(true);
                    $spreadsheet->getSecurity()->setRevisionsPassword($password);
                    $spreadsheet->getSecurity()->setworkbookPassword($password);

                    $writer = new Xlsx($spreadsheet);
                    $writer->save($absoluteTempPath);


                    $excelFilePath = storage_path('app/public/palawan/' . $field . '/' . $program . '/' . $field . '_' . $program . '.xlsx');
                    $zipFilePath = storage_path('app/public/palawan/' . $field . '/' . $program . '/' . $field . '_' . $program . '.zip');

                    // Create the zip file with password using 7-Zip
                    $zipCommand = '"' . $sevenZipPath . '" a -tzip "' . $zipFilePath . '" "' . $excelFilePath . '" -p' . $password;
                    shell_exec($zipCommand);

                    // Delete the original Excel file after zipping
                    if (file_exists($excelFilePath)) {
                        unlink($excelFilePath); // Deletes the file
                    }           

                } catch (\Exception $e) {
                    \Log::error("Failed to export $field - $program: " . $e->getMessage());
                }
            }
        }
        Artisan::call('email:process');
        return redirect()->back()->with('success', 'File imported successfully.');
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
            $tens = (int)($presum / 10) * 10;
            $remainder = $presum % 10;
            return $words[$tens] . ($remainder > 0 ? '-' . $this->spellNumber($remainder) : '');
        }
    
        if ($presum < 1000) {
            $hundreds = (int)($presum / 100);
            $remainder = $presum % 100;
            return $words[$hundreds] . ' hundred' . ($remainder > 0 ? ' ' . $this->spellNumber($remainder) : '');
        }
    
        if ($presum < 1000000) {
            $thousands = (int)($presum / 1000);
            $remainder = $presum % 1000;
            return $this->spellNumber($thousands) . ' thousand' . ($remainder > 0 ? ' ' . $this->spellNumber($remainder) : '');
        }
    
        if ($presum < 1000000000) {
            $millions = (int)($presum / 1000000);
            $remainder = $presum % 1000000;
            return $this->spellNumber($millions) . ' million' . ($remainder > 0 ? ' ' . $this->spellNumber($remainder) : '');
        }
    
        return 'Number out of range';
    }

    public function sampletext()
    {
        $phoneNumbers = ['+639074416182',];
        $name = explode(' ', Auth::user()->name);
        $greetings = "Test Good day Mr./Ms. " /*. strtoupper($name[count($name)-1]) . ",\n"*/;
        $message = "This is to inform you that.";
        //$sender = 'CASHIER - Kenneth';

        foreach ($phoneNumbers as $phoneNumber) {
            $response = Http::withBasicAuth('', '') //i dont mind exposed credentials, needs my phone lol
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post(env('SMS_GATE_API_URL'), [
                    'message' => $greetings . $message,
                    'phoneNumbers' => [$phoneNumber], 
                    /*'from' => $sender,*/ //To review documentation if custom sender name allowed
                ]);

            if ($response->successful()) {
                // Handle successful response
                echo "Message sent to {$phoneNumber}: " . json_encode($response->json()) . "\n";
            } else {
                // Handle error
                echo "Failed to send message to {$phoneNumber}: " . $response->body() . "\n";
            }
        }
    }

}

/*For addition

Artisan::call('email:process');*/