<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $subject }}</title>
        <style>
            p {
                font-family: "Century Gothic";
            }
        </style>
    </head>
    <body>
        <img src="https://magblogjapunmo.files.wordpress.com/2024/02/head-1.png" alt="Header Image" style="max-width:100%;">
        <p>Sir/Ma'am:</p>
        <p>Good day!</p>
        <p>Kindly see attached file for the List of Unclaimed Palawan Transactions as of {{ date('m/d/Y') }}.<br>
        Please notify the beneficiaries to ensure that the transactions are claimed within the 60 day period from the date of posting (which can be found inside the file).</p>
        <p>To view the contents of the file, please enter <strong>"{{ $password }}"</strong> (without the quotation marks) as the password inside the excel file.</p>
        <p>Furthermore, this is to remind you that the <strong>TRANSACTION CODES</strong> are <strong style='color:red'>STRICTLY CONFIDENTIAL</strong> in nature.</p>
        <p>Thank you and God Bless.</p>
        <p>Yours truly,</p>
        <br>
        <p><strong style='color:blue'>KENNETH ANGELO T. BARENG</strong><br>
        -Cashier Staff</p>

        {{-- You can include any dynamic content here using Blade syntax --}}
    </body>
</html>