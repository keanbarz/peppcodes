<!DOCTYPE html>
<html>
    <head>
        <title>List of  Transactions for the Year </title>
        <style>/*Tables+Input Box+Button*/
            /* Define styles for the table */
            table {
                width: 100%;
                border-collapse: collapse;
                font-family:  Arial, sans-serif;
                }

            /* Style table headers */
            tr {
                /* Add border to table header cells */
                page-break-inside: avoid;
            }
            th {
                padding: 10px;
                min-width: unset;
                border: 1px solid black; /* Add border to table header cells */
                }
            td {
                padding: 2px;
                min-width: unset;
                text-align: left;
                border: 1px solid black; /* Add border to table data cells */
                } 
                
            @page :first {
                /*margin: 0in 0.4in 0.5in 0.4in;*/
                    /* top, right, bottom, left */
            }

            @page {
                margin: 0.2in 0.2in 0.2in 0.2in;
                font-family:  Arial, sans-serif;
                font-size: 10;
            }
            .page-break {
                page-break-before: always;
            }
            .footer {
                position: absolute;
                bottom: -30;
                width: 100%;
                text-align: center;
                /*border-top: 1px solid black;*/
                padding: 5px;
                /*font-size: 12px;*/
            }
            .prepared {
                position: absolute;
                bottom: -10;
                left: -3;
                width: 100%;
                padding: 5px;
            }

            .page-number::before {
                content: "Page " counter(page) " of ";
            }
            .container {
            display: flex; /* Enables flexbox layout */
            /*justify-content: space-between; /* Distributes divs with space between them */
            /*gap: 10px; /* Optional: Adds spacing between the divs */
            }

            .box {
                /*border: 1px solid #ccc;*/
            }
        </style>
    </head>
    <body>
        <!-- LBP P1 Header -->
        <div>
            <div class="container" style="width: 100%; display:table;">
                <div class="box" style="width: 30%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10;">LAND BANK OF THE PHILIPPINES<br>
                    C. M. RECTO<br>
                    C. M. RECTO, DAVAO CITY<br>
                    DATE PREPARED 12/13/2024
                    </p>
                </div>
                <div class="box" style="width: 43%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10; text-align: center;">DEPARTMENT OF LABOR AND EMPLOYMENT
                    QUIMPO BOULEVARD, BRGY. 74-A MATINA
                    CROSSING, DAVAO CITY</p>
                </div>                
                <div class="box" style="width: 27%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10;">ACIC NO.: insert code<br>
                    ORG CODE:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;160010300011<br>
                    FUNDING SOURCE:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;01101101<br>
                    AREA CODE:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1190<br>
                    ALLOCATION NO : insert code
                    </p>
                </div>  
            </div>
            <div style="text-align: center; font-size: 10;">
                <strong>ADVICE OF CHECKS ISSUED AND CANCELLED</strong>
            </div>
        </div><br>
        <!-- LBP P1 Data -->
        <div>
            <span style=" font-size: 10;">ACCOUNT NO.: 2016-9032-59</span>
            <table>
                <thead>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align:center; border: none; background-color: lightgray; width:11.29%;">CHECK NO.</td>
                        <td style="text-align:center; border: none; background-color: lightgray; white-space: nowrap; width:14.52%;">DATE OF ISSUE</td>
                        <td style="text-align:center; border: none; background-color: lightgray; width:33.87%;">PAYEE</td>
                        <td style="text-align:center; border: none; background-color: lightgray; width:17.74%;">AMOUNT</td>
                        <td style="text-align:center; border: none; background-color: lightgray; width:11.29%;">OBJ CODE</td>
                        <td style="text-align:center; border: none; background-color: lightgray; width:9.68%">REMARKS</td>
                    </tr>
                    @foreach ($acics as $acic)
                        <tr>
                            <td style="font-family: sans-serif; font-size: 10; text-align:right;">{{ str_pad($acic->check_number,10,'0', STR_PAD_LEFT)}} </td>
                            <td style="font-family: sans-serif; font-size: 10; text-align:center;">{{ preg_replace('/(\d{1,2})\/(\d)\/(\d{4})/', '$1/0$2/$3',$acic->check_date)}} </td>
                            <td style="font-family: sans-serif; font-size: 10;">{{ substr($acic->payee,0,30)}} </td>
                            <td style="text-align: right;">{{ number_format(($acic->amount),2) }} </td>
                            <td style="font-family: sans-serif; font-size: 10;  text-align:center;">{{ $acic->uacs}} </td>
                            <td> </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div>
            <table>
                <tbody>
                    <tr>
                        <td style="text-align:left; white-space: nowrap; border: none;  width:19.35%; padding: 1px;">TOTAL ACIC AMOUNT :&nbsp;</td>
                        <td style="text-align:left; border: none;  width:50%; padding: 1px;">{{number_format($sum,2)}}</td>                        
                        <td style="text-align:left; white-space: nowrap; border: none;  width:20.97%; padding: 1px;">TOTAL NO. OF CHECKS :</td>
                        <td style="text-align:left; border: none;  width:9.68%; padding: 1px;">&nbsp;{{$acics->count()}}</td>
                    </tr>
                </tbody>
            </table>
            <table>
                <tbody>
                    <tr>
                        <td style="text-align:left; border: none;  width:19.35%; padding: 0px;">AMOUNT IN WORDS :<br> <span style="color:white;">placeholder</span></td>
                        <td style="text-align:left; border: none;  width:75%; padding: 0px;">{{$inwords}}</td>
                        <td style="text-align:left; border: none;  width:4.68%; padding: 0px;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- LBP Footer -->
        <div class="prepared">
            <div class="container" style="width: 100%; display:table; justify-content: space-between;">
                <div class="box" style="width: 36.10%;display: table-cell;"><br>
                    <table>
                        <tr>
                            <td colspan="3" style="padding: 1px; text-align: center; border-bottom: none;">CANCELLED CHECKS</td>
                        </tr>
                        <tr>
                            <td style="padding: 0px; text-align: center; border-top:none; border-bottom: none;">CHECK NO</td>
                            <td style="padding: 0px; text-align: center; border-top:none; border-bottom: none;">CHECK DATE</td>
                            <td style="padding: 0px; text-align: center; border-top:none; border-bottom: none;">REMARKS</td>
                        </tr>
                        <tr>
                            <td style="padding: 1px; text-align: center; border-top:none; border-bottom: none; color:white;">None</td>
                            <td style="padding: 1px; text-align: center; border-top:none; border-bottom: none; color:white;">None</td>
                            <td style="padding: 1px; text-align: center; border-top:none; border-bottom: none; color:white;">None</td>
                        </tr>
                        <tr>
                            <td style="padding: 1px; text-align: center; border-top:none; border-bottom: none; border-right:none; color:white;">None</td>
                            <td style="padding: 1px; text-align: center; border-top:none; border-bottom: none; border-left:none; border-right:none; color:white;">None</td>
                            <td style="padding: 1px; text-align: center; border-top:none; border-bottom: none; border-left:none; color:white;">None</td>
                        </tr>
                        <tr>
                            <td style="padding: 1px; text-align: center; border-top:none; border-bottom: none; border-right:none; color:white;">None</td>
                            <td style="padding: 1px; text-align: center; border-top:none; border-bottom: none; border-left:none; border-right:none; color:white;">None</td>
                            <td style="padding: 1px; text-align: center; border-top:none; border-bottom: none; border-left:none; color:white;">None</td>
                        </tr>
                        <tr>
                            <td style="padding: 1px; text-align: center; border-top:none; border-right:none; color:white;">None</td>
                            <td style="padding: 1px; text-align: center; border-top:none; border-left:none; border-right:none; color:white;">None</td>
                            <td style="padding: 1px; text-align: center; border-top:none; border-left:none; color:white;">None</td>
                        </tr>
                    </table>
                </div>
                <div class="box" style="width: 1%;display: table-cell;"></div>
                <div class="box" style="width: 22.80%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10; white-space: nowrap; left: 3;">CERTIFIED CORRECT BY:<br>
                        ___________________<br>
                        <span style="font-size: 9;">NOVIE JANE B. PANIAGUA</span><br>
                        <br>
                        APPROVED BY:<br>
                        ___________________<br>
                        <span style="font-size:9;">ATTY. JASON P. BALAIS</span>
                    </p>
                </div>
                <div class="box" style="width: 5%;display: table-cell;"></div>                
                <div class="box" style="width: 17.05%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10; ;">VERIFIED BY:<br>
                        _________________<br>
                        <br>
                        <br>
                        POSTED BY:<br>
                        _________________
                    </p>
                </div>
                <div class="box" style="width: 1%;display: table-cell;"></div>                
                <div class="box" style="width: 17.05%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10;">RECEIVED BY:<br>
                        _________________<br>
                        <br>
                        <br>
                        DELIVERED BY:<br>
                        _________________
                    </p>
                </div>  
            </div>
            <div>
                <div class="container" style="width: 100%; display:table;">
                    <div class="box" style="width: 30%;display: table-cell;">
                        <p style="font-family: sans-serif; font-size: 10;">**FILENAME: &nbsp;D:\DOLEacicno.txt</p>
                    </div>
                    <div class="box" style="width: 30%;display: table-cell;">
                        <p style="font-family: sans-serif; font-size: 10; text-align: right;"><strong>** FOR LBP USE ONLY **</strong><br>
                            <span>Page 1 of 2</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- LBP P2 Header -->
        <div class="page-break">
            <div class="container" style="width: 100%; display:table;">
                <div class="box" style="width: 30%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10;">
                    </p>
                </div>
                <div class="box" style="width: 43%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10; text-align: center;">LANDBANK OF THE PHILIPPINES<br>
                        C. M. RECTO<br>
                        C. M. RECTO, DAVAO CITY<br>
                        12/13/2024<br>
                        <strong>ADVICE OF CHECKS ISSUED AND CANCELLED</strong><br>
                        REPORT SUMMARY
                    </p>
                </div>                
                <div class="box" style="width: 27%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10;">
                    </p>
                </div>  
            </div>
            <div class="container" style="width: 100%; display:table;">
                <table>
                    <tr>
                        <td colspan="3" style="border-top:none; border-left:none; border-right:none;"></td>
                    </tr>
                    <tr>
                        <td style="text-align:left; white-space: nowrap; border: none;  width:20%; padding: 1px;">NUMBER OF ACIC(S): &nbsp;</td>
                        <td style="text-align:left; border: none;  width:75%; padding: 1px;">1</td>
                        <td style="text-align:left; border-left: none; border-top: none; border-right: none;  width:5%; padding: 0px;"></td>                        
                    </tr>
                    <tr>
                        <td style="text-align:left; white-space: nowrap; border: none;  width:20%; padding: 1px;">GRAND TOTAL :&nbsp;</td>
                        <td style="text-align:left; border: none;  width:75%; padding: 1px;">{{number_format($sum,2)}}</td>                        
                    </tr>
                    <tr>
                        <td style="text-align:left; border-left: none; border-top: none; border-right: none; white-space: nowrap; width:20%; padding: 0px;">AMOUNT IN WORDS :<br> <span style="color:white;">placeholder</span></td>
                        <td style="text-align:left; border-left: none; border-top: none; border-right: none;  width:75%; padding: 0px;">{{$inwords}}</td>
                        <td style="text-align:left; border-left: none; border-top: none; border-right: none;  width:5%; padding: 0px;"></td>
                    </tr>
                </table>
            </div>
            <div class="container" style="width: 100%; display:table;">
                <div class="box" style="width: 22.80%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10; white-space: nowrap; left: 3;">CERTIFIED CORRECT BY:<br>
                        ___________________<br>
                        <span style="font-size: 9;">NOVIE JANE B. PANIAGUA</span><br>
                        <br>
                        APPROVED BY:<br>
                        ___________________<br>
                        <span style="font-size:9;">ATTY. JASON P. BALAIS</span>
                    </p>
                </div>
                <div class="box" style="width: 5%;display: table-cell;"></div>                
                <div class="box" style="width: 17.05%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10; ;">VERIFIED BY:<br>
                        _________________<br>
                        <br>
                        <br>
                        POSTED BY:<br>
                        _________________
                    </p>
                </div>
                <div class="box" style="width: 1%;display: table-cell;"></div>                
                <div class="box" style="width: 17.05%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10;">RECEIVED BY:<br>
                        _________________<br>
                        <br>
                        <br>
                        DELIVERED BY:<br>
                        _________________
                    </p>
                </div>
                <div class="box" style="width: 37.1%;display: table-cell;"></div>                
            </div>
            <div class="container" style="width: 100%; display:table;">
                <table>
                    <tr>
                        <td colspan="3" style="border-top:none; border-left:none; border-right:none;"></td>
                    </tr>
                    <tr>
                        <td style="text-align:left; white-space: nowrap; border: none;  width:20%; padding: 1px;">** <strong>FOR LBP USE ONLY</strong></td>
                        <td style="text-align:right; border: none;  width:75%; padding: 1px;">**</td>
                        <td style="text-align:right; border: none;  width:75%; padding: 1px;"></td>                                                
                    </tr>
                    <tr>
                        <td style="text-align:left; white-space: nowrap;border-left: none; border-top: none; border-right: none;  width:20%; padding: 1px;">** <strong>HASH TOTAL:</strong></td>
                        <td style="text-align:right; border-left: none; border-top: none; border-right: none;  width:; padding: 1px;">{{number_format($hash_total,2)}} &nbsp;**</td>    
                        <td style="text-align:right; border: none;  width:; padding: 1px;">Page 2 of 2</td>                                            
                    </tr>
                </table>
            </div>
        </div>
        <!-- BTR P1 Header -->
        <div class="page-break">
            <div class="container" style="width: 100%; display:table;">
                <div class="box" style="width: 30%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10;">LAND BANK OF THE PHILIPPINES<br>
                    C. M. RECTO<br>
                    C. M. RECTO, DAVAO CITY<br>
                    DATE PREPARED 12/13/2024
                    </p>
                </div>
                <div class="box" style="width: 43%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10; text-align: center;">DEPARTMENT OF LABOR AND EMPLOYMENT
                    QUIMPO BOULEVARD, BRGY. 74-A MATINA
                    CROSSING, DAVAO CITY</p>
                </div>                
                <div class="box" style="width: 27%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10;">ACIC NO.: insert code<br>
                    ORG CODE:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;160010300011<br>
                    FUNDING SOURCE:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;01101101<br>
                    AREA CODE:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1190<br>
                    ALLOCATION NO : insert code
                    </p>
                </div>  
            </div>
            <div style="text-align: center; font-size: 10;">
                <strong>ADVICE OF CHECKS ISSUED AND CANCELLED</strong>
            </div>
        </div><br>
        <div>
            <span style=" font-size: 10;">ACCOUNT NO.: 2016-9032-59</span>
            <table>
                <thead>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align:center; border: none; background-color: lightgray; width:11.29%;">CHECK NO.</td>
                        <td style="text-align:center; border: none; background-color: lightgray; white-space: nowrap; width:14.52%;">DATE OF ISSUE</td>
                        <td style="text-align:center; border: none; background-color: lightgray; width:33.87%;">PAYEE</td>
                        <td style="text-align:center; border: none; background-color: lightgray; width:17.74%;">AMOUNT</td>
                        <td style="text-align:center; border: none; background-color: lightgray; width:11.29%;">OBJ CODE</td>
                        <td style="text-align:center; border: none; background-color: lightgray; width:9.68%">REMARKS</td>
                    </tr>
                    @foreach ($acics as $acic)
                        <tr>
                            <td style="font-family: sans-serif; font-size: 10; text-align:right;">{{ str_pad($acic->check_number,10,'0', STR_PAD_LEFT)}} </td>
                            <td style="font-family: sans-serif; font-size: 10; text-align:center;">{{ preg_replace('/(\d{1,2})\/(\d)\/(\d{4})/', '$1/0$2/$3',$acic->check_date)}} </td>
                            <td style="font-family: sans-serif; font-size: 10;">{{ substr($acic->payee,0,30)}} </td>
                            <td style="text-align: right;">{{ number_format(($acic->amount),2) }} </td>
                            <td style="font-family: sans-serif; font-size: 10;  text-align:center;">{{ $acic->uacs}} </td>
                            <td> </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div>
            <table>
                <tbody>
                    <tr>
                        <td style="text-align:left; white-space: nowrap; border: none;  width:19.35%; padding: 1px;">TOTAL ACIC AMOUNT :&nbsp;</td>
                        <td style="text-align:left; border: none;  width:50%; padding: 1px;">{{number_format($sum,2)}}</td>                        
                        <td style="text-align:left; white-space: nowrap; border: none;  width:20.97%; padding: 1px;">TOTAL NO. OF CHECKS :</td>
                        <td style="text-align:left; border: none;  width:9.68%; padding: 1px;">&nbsp;{{$acics->count()}}</td>
                    </tr>
                </tbody>
            </table>
            <table>
                <tbody>
                    <tr>
                        <td style="text-align:left; border: none;  width:19.35%; padding: 0px;">AMOUNT IN WORDS :<br> <span style="color:white;">placeholder</span></td>
                        <td style="text-align:left; border: none;  width:75%; padding: 0px;">{{$inwords}}</td>
                        <td style="text-align:left; border: none;  width:4.68%; padding: 0px;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="prepared">
            <div class="container" style="width: 100%; display:table; justify-content: space-between;">
                <div class="box" style="width: 36.10%;display: table-cell;"><br>
                    <table>
                        <tr>
                            <td colspan="3" style="padding: 1px; text-align: center; border-bottom: none;">CANCELLED CHECKS</td>
                        </tr>
                        <tr>
                            <td style="padding: 0px; text-align: center; border-top:none; border-bottom: none;">CHECK NO</td>
                            <td style="padding: 0px; text-align: center; border-top:none; border-bottom: none;">CHECK DATE</td>
                            <td style="padding: 0px; text-align: center; border-top:none; border-bottom: none;">REMARKS</td>
                        </tr>
                        <tr>
                            <td style="padding: 1px; text-align: center; border-top:none; border-bottom: none; color:white;">None</td>
                            <td style="padding: 1px; text-align: center; border-top:none; border-bottom: none; color:white;">None</td>
                            <td style="padding: 1px; text-align: center; border-top:none; border-bottom: none; color:white;">None</td>
                        </tr>
                        <tr>
                            <td style="padding: 1px; text-align: center; border-top:none; border-bottom: none; border-right:none; color:white;">None</td>
                            <td style="padding: 1px; text-align: center; border-top:none; border-bottom: none; border-left:none; border-right:none; color:white;">None</td>
                            <td style="padding: 1px; text-align: center; border-top:none; border-bottom: none; border-left:none; color:white;">None</td>
                        </tr>
                        <tr>
                            <td style="padding: 1px; text-align: center; border-top:none; border-bottom: none; border-right:none; color:white;">None</td>
                            <td style="padding: 1px; text-align: center; border-top:none; border-bottom: none; border-left:none; border-right:none; color:white;">None</td>
                            <td style="padding: 1px; text-align: center; border-top:none; border-bottom: none; border-left:none; color:white;">None</td>
                        </tr>
                        <tr>
                            <td style="padding: 1px; text-align: center; border-top:none; border-right:none; color:white;">None</td>
                            <td style="padding: 1px; text-align: center; border-top:none; border-left:none; border-right:none; color:white;">None</td>
                            <td style="padding: 1px; text-align: center; border-top:none; border-left:none; color:white;">None</td>
                        </tr>
                    </table>
                </div>
                <div class="box" style="width: 1%;display: table-cell;"></div>
                <div class="box" style="width: 22.80%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10; white-space: nowrap; left: 3;">CERTIFIED CORRECT BY:<br>
                        ___________________<br>
                        <span style="font-size: 9;">NOVIE JANE B. PANIAGUA</span><br>
                        <br>
                        APPROVED BY:<br>
                        ___________________<br>
                        <span style="font-size:9;">ATTY. JASON P. BALAIS</span>
                    </p>
                </div>
                <div class="box" style="width: 5%;display: table-cell;"></div>                
                <div class="box" style="width: 17.05%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10; ;">VERIFIED BY:<br>
                        _________________<br>
                        <br>
                        <br>
                        POSTED BY:<br>
                        _________________
                    </p>
                </div>
                <div class="box" style="width: 1%;display: table-cell;"></div>                
                <div class="box" style="width: 17.05%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10;">RECEIVED BY:<br>
                        _________________<br>
                        <br>
                        <br>
                        DELIVERED BY:<br>
                        _________________
                    </p>
                </div>  
            </div>
            <div>
                <div class="container" style="width: 100%; display:table;">
                    <div class="box" style="width: 30%;display: table-cell;">
                        <p style="font-family: sans-serif; font-size: 10;">**FILENAME: &nbsp;D:\DOLEacicnoBTR.txt</p>
                    </div>
                    <div class="box" style="width: 30%;display: table-cell;">
                        <p style="font-family: sans-serif; font-size: 10; text-align: right;"><strong>** FOR BTR USE ONLY **</strong><br>
                            <span>Page 1 of 2</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-break">
            <div class="container" style="width: 100%; display:table;">
                <div class="box" style="width: 30%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10;">
                    </p>
                </div>
                <div class="box" style="width: 43%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10; text-align: center;">LANDBANK OF THE PHILIPPINES<br>
                        C. M. RECTO<br>
                        C. M. RECTO, DAVAO CITY<br>
                        12/13/2024<br>
                        <strong>ADVICE OF CHECKS ISSUED AND CANCELLED</strong><br>
                        REPORT SUMMARY
                    </p>
                </div>                
                <div class="box" style="width: 27%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10;">
                    </p>
                </div>  
            </div>
            <div class="container" style="width: 100%; display:table;">
                <table>
                    <tr>
                        <td colspan="3" style="border-top:none; border-left:none; border-right:none;"></td>
                    </tr>
                    <tr>
                        <td style="text-align:left; white-space: nowrap; border: none;  width:20%; padding: 1px;">NUMBER OF ACIC(S): &nbsp;</td>
                        <td style="text-align:left; border: none;  width:75%; padding: 1px;">1</td>
                        <td style="text-align:left; border-left: none; border-top: none; border-right: none;  width:5%; padding: 0px;"></td>                        
                    </tr>
                    <tr>
                        <td style="text-align:left; white-space: nowrap; border: none;  width:20%; padding: 1px;">GRAND TOTAL :&nbsp;</td>
                        <td style="text-align:left; border: none;  width:75%; padding: 1px;">{{number_format($sum,2)}}</td>                        
                    </tr>
                    <tr>
                        <td style="text-align:left; border-left: none; border-top: none; border-right: none; white-space: nowrap; width:20%; padding: 0px;">AMOUNT IN WORDS :<br> <span style="color:white;">placeholder</span></td>
                        <td style="text-align:left; border-left: none; border-top: none; border-right: none;  width:75%; padding: 0px;">{{$inwords}}</td>
                        <td style="text-align:left; border-left: none; border-top: none; border-right: none;  width:5%; padding: 0px;"></td>
                    </tr>
                </table>
            </div>
            <div class="container" style="width: 100%; display:table;">
                <div class="box" style="width: 22.80%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10; white-space: nowrap; left: 3;">CERTIFIED CORRECT BY:<br>
                        ___________________<br>
                        <span style="font-size: 9;">NOVIE JANE B. PANIAGUA</span><br>
                        <br>
                        APPROVED BY:<br>
                        ___________________<br>
                        <span style="font-size:9;">ATTY. JASON P. BALAIS</span>
                    </p>
                </div>
                <div class="box" style="width: 5%;display: table-cell;"></div>                
                <div class="box" style="width: 17.05%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10; ;">VERIFIED BY:<br>
                        _________________<br>
                        <br>
                        <br>
                        POSTED BY:<br>
                        _________________
                    </p>
                </div>
                <div class="box" style="width: 1%;display: table-cell;"></div>                
                <div class="box" style="width: 17.05%;display: table-cell;">
                    <p style="font-family: sans-serif; font-size: 10;">RECEIVED BY:<br>
                        _________________<br>
                        <br>
                        <br>
                        DELIVERED BY:<br>
                        _________________
                    </p>
                </div>
                <div class="box" style="width: 37.1%;display: table-cell;"></div>                
            </div>
            <div class="container" style="width: 100%; display:table;">
                <table>
                    <tr>
                        <td colspan="2" style="border-top:none; border-left:none; border-right:none;"></td>
                    </tr>
                    <tr>
                        <td style="text-align:left; white-space: nowrap;border-left: none; border-top: none; border-right: none;  width:20%; padding: 3px;">**<strong>FOR BTR USE ONLY</strong></td>
                        <td style="text-align:right; border-left: none; border-top: none; border-right: none;  width:; padding: 3px;"></td>    
                        <td style="text-align:right; border: none;  width:; padding: 3px;">Page 2 of 2</td>                                            
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>
