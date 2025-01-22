<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class EmailProcessingCommand extends Command
{
    /*
     *
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:process';

    /*
     *
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /*
     *
     * Execute the console command.
     */
    public function handle()
    {

        // Value initialization
        $day = 0;
        $count = 01;
        $amount = 0;
        $newDateString = '';
        $xnewDateString = '';

            // Specify the root folder path within the storage directory
            $rootFolderPath = storage_path('app\public\palawan');
            // Get all folders in the root folder
            $folders = File::directories($rootFolderPath);
            
            foreach ($folders as $folder) {
                // Get the folder name
                $folderName = basename($folder);
                $subFolders = File::directories($folder);
                // Sub Loop           
                foreach ($subFolders as $subFolder){

                    $files = File::allFiles($subFolder);
                    $subFolderName = basename($subFolder);

                    // Email and File Password Template
                    $destinationEmail = $this->getEmailForFolder($folderName, $subFolderName);
                    $subject = $folderName . ' ' . $subFolderName . ' - Unclaimed Palawan Transaction/s as of ' . date('m/d/Y');  // Replace with your desired subject
                    $password = $subFolderName[0] . 'dole11' . strtolower($folderName) . date('mdy');
                    // Send Mail, Skip if Empty
                    if (empty($files)) {
                        $this->info(sprintf("%02d", $count) . '. ' . $folderName . '-' . $subFolderName . ' has no Transactions. Skipping sending email.');
                        //\Log::info($count);
                    }
                    else {$this->info(sprintf("%02d", $count) . '. Sending Transactions to ' . $folderName . '-' . $subFolderName . '... (' . $destinationEmail . ')');
                       $this->sendEmail($destinationEmail, $files, $subject, $newDateString, $subFolderName, $xnewDateString, $password);
                    }

                // Delete files after sending; Increment Log Count
                    File::delete($files);
                    $count = $count + 1;
                }
            }

        echo "Transactions emailed successfully.";
        echo "Press Enter to continue...";
        readline();
    }    

    /**
     * Get the email address based on the folder name.
     *
     * @param string
     * @return string
     */
    function getEmailForFolder($folderName, $subFolderName)
    {
        switch ($folderName) 
        {
            case 'DORFO':
                switch ($subFolderName)
                    {
                        case 'GIP':
                        case 'SPES':
                            return 'lmpvillarindole11@gmail.com';
                        default:
                            return strtolower($subFolderName . 'remittance' . $folderName . 'dole11@gmail.com');
                    }
            case 'DOCFO':
                return 'remittancedocfodole11@gmail.com';
            case 'DCFO':
                switch ($subFolderName)
                    {
                        case 'GIP':
                            return 'dole11dcfo21@gmail.com';
                        case 'SPES':
                        case 'TUPAD':
                            return strtolower($subFolderName . 'remit' . $folderName . 'dole11@gmail.com');
                    }
            case 'DSFO':
                switch ($subFolderName)
                    {
                        case 'GIP':
                            return strtolower($subFolderName . 'remittance' . $folderName . '@gmail.com');
                        case 'SPES':
                            return strtolower($subFolderName . 'remit' . $folderName . 'dole11@gmail.com');
                        default:
                            return strtolower($subFolderName . 'remittance' . $folderName . 'dole11@gmail.com');
                    }                    
            case 'DNFO':
                switch ($subFolderName)
                    {
                        case 'TUPAD':
                            return strtolower($subFolderName . 'remittancedole11' . $folderName . '@gmail.com');
                        default:
                            return strtolower($subFolderName . 'remittance' . $folderName . 'dole11@gmail.com');
                    }
            case 'DIEO':
                switch ($subFolderName)
                    {
                        case 'GIP':
                            return strtolower($subFolderName . 'remit' . $folderName . 'dole11@gmail.com');
                        default:
                            return strtolower($subFolderName . 'remittance' . $folderName . 'dole11@gmail.com');
                    }
            case 'ROXI':
                switch ($subFolderName)
                    {
                        case 'SPES':
                        case 'GIP':
                            return 'employmentremittancerodole11@gmail.com';
                        default:
                            return 'livelihood@r11.dole.gov.ph';
                    }
                    
            default:
            return strtolower($subFolderName . 'remittance' . $folderName . 'dole11@gmail.com');
        }
    }

    /*
     * Send an email.
     *
     * @param string $destinationEmail
     * @param string $subject
     * @param string $content
     */
    function sendEmail($destinationEmail, $files, $subject, $newDateString, $subFolderName, $xnewDateString, $password)
    {
        $mailData = [
            'subject' => $subject,
            'xnewDateString' => $xnewDateString,
            'newDateString' => $newDateString,
            'password' => $password,
        ];

        Mail::send('email', $mailData, function ($message) use ($destinationEmail, $subject, $files, $subFolderName) {
            $message->to($destinationEmail)->subject($subject);

            switch ($subFolderName) {
                case 'GIP':
                case 'SPES':
                    #employmentremittancerodole11
                    if ($destinationEmail == 'employmentremittancerodole11@gmail.com') {
                        break;
                    }
                    else {
                        $message->cc('employmentremittancerodole11@gmail.com');
                    }
                    break;
                default:
                break;
            }

            foreach ($files as $file) {
                // Attach each file to the email
                $message->attach($file);
            }
        });
    }
    
    function convertNumberToWords($amount) 
    {
        $words = [
            0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen',
            20 => 'twenty', 30 => 'thirty', 40 => 'forty', 50 => 'fifty', 60 => 'sixty', 70 => 'seventy', 80 => 'eighty', 90 => 'ninety'
        ];
    
        if ($amount < 20) {
            return $words[$amount];
        }
    
        if ($amount < 100) {
            return $words[(($amount / 10) * 10)-($amount % 10)] . '-' . $this->convertNumberToWords($amount % 10);
        }
    
        if ($amount < 1000) {
            return $words[$amount / 100] . ' hundred ' . $this->convertNumberToWords($amount % 100);
        }
    
        if ($amount < 1000000) {
            return $this->convertNumberToWords($amount / 1000) . ' thousand ' . $this->convertNumberToWords($amount % 1000);
        }

        if ($amount < 1000000000) {
            return $this->convertNumberToWords($amount / 1000000) . ' million ' . $this->convertNumberToWords($amount % 1000000);
        }    

        return 'Number out of range';
    }

}
