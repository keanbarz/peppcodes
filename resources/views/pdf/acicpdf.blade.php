<!DOCTYPE html>
<html>
    <head>
        <title>ADVICE OF CHECKS ISSUED AND CANCELLED</title>
        <style>
            /* Define styles for the table */
            table {
                width: 100%;
                border-collapse: collapse;
                font-family:  Arial, sans-serif;
                }
            
            .column-title {
                text-align:center;
                border: none;
                background-color: lightgray;
            }
            /* Style table headers */
            tr {
                page-break-inside: avoid;
            }
            td {
                padding: 2px;
                min-width: unset;
                text-align: left;
                border: 1px solid black; /* Add border to table data cells */
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
                display: flex;
            }

        </style>
    </head>
    <body>
        <!-- LBP P1 Header -->
        <div>
            <div class="container" style="width: 100%; display:table;">
                <div style="width: 27%; display: table-cell;">
                    <p style="white-space: nowrap;">LAND BANK OF THE PHILIPPINES<br>
                    C. M. RECTO<br>
                    C. M. RECTO, DAVAO CITY<br>
                    DATE PREPARED {{ \Carbon\Carbon::now()->format('n/j/Y') }}
                    </p>
                </div>
                <div style="width: 47%;display: table-cell;">
                    <p style="text-align: center;">DEPARTMENT OF LABOR AND EMPLOYMENT
                    QUIMPO BOULEVARD, BRGY. 74-A MATINA
                    CROSSING, DAVAO CITY</p>
                </div>                
                <div style="width: 23%;display: table-cell;">
                    <p style="white-space: nowrap;">ACIC NO.: <br>
                    ORG CODE:<br>
                    FUNDING SOURCE:<br>
                    AREA CODE:<br>
                    ALLOCATION NO : 
                    </p>
                </div><div style="width: 2%;display: table-cell;">
                    <p style="text-align:right;">{{$acicno}}<br>
                    160010300011<br>01101101<br>1190<br>{{$nca}}
                    </p>
                </div>  
            </div>
            <div style="text-align: center;">
                <strong>ADVICE OF CHECKS ISSUED AND CANCELLED</strong>
            </div>
        </div><br>
        <!-- LBP P1 Data -->
        <div>
            <span>ACCOUNT NO.: 2016-9032-59</span>
            <table>
                <thead>
                </thead>
                <tbody>
                    <tr>
                        <td class="column-title" style="width:11.29%;">CHECK NO.</td>
                        <td class="column-title" style="width:14.52%; white-space: nowrap; ">DATE OF ISSUE</td>
                        <td class="column-title" style="width:33.87%;">PAYEE</td>
                        <td class="column-title" style="width:17.74%;">AMOUNT</td>
                        <td class="column-title" style="width:11.29%;">OBJ CODE</td>
                        <td class="column-title" style="width:9.68%">REMARKS</td>
                    </tr>
                    @foreach ($acics as $acic)
                        <tr>
                            <td style="text-align:right; ">{{ str_pad($acic->check_number,10,'0', STR_PAD_LEFT)}} </td>
                            <td style="text-align:center;">{{ preg_replace('/(\d{1,2})\/(\d)\/(\d{4})/', '$1/0$2/$3',$acic->check_date)}} </td>
                            <td>{{ substr($acic->payee,0,40)}} </td>
                            <td style="text-align: right;">{{ number_format(($acic->amount),2) }} </td>
                            <td style="text-align:center;">{{ $acic->uacs}} </td>
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
                        <td style="white-space: nowrap; border: none;  width:21%; padding: 1px;">TOTAL ACIC AMOUNT :&nbsp;</td>
                        <td style="border: none;  width:51%; padding: 1px;">&nbsp;{{number_format($sum,2)}}</td>                        
                        <td style="white-space: nowrap; border: none;  width:19%; padding: 1px;">TOTAL NO. OF CHECKS :</td>
                        <td style="border: none;  width:9%; padding: 1px;">&nbsp;{{$acics->count()}}</td>
                    </tr>
                </tbody>
            </table>
            <table>
                <tbody>
                    <tr>
                        <td style="border: none;  width:21%; padding: 0px; display:flex; white-space: nowrap;">AMOUNT IN WORDS :</td>
                        <td style="border: none;  width:79%; padding: 0px; ">{{$inwords}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- LBP Footer -->
        <div class="prepared">
            <div class="container" style="width: 100%; display:table; justify-content: space-between;">
                <div style="width: 36.10%;display: table-cell;"><br>
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
                <div style="width: 1%;display: table-cell;"></div>
                <div style="width: 22.80%;display: table-cell;">
                    <p style="white-space: nowrap; left: 3;">CERTIFIED CORRECT BY:<br>
                        ___________________<br>
                        <span style="font-size: 9;">{{$ccb}}</span><br>
                        <br>
                        APPROVED BY:<br>
                        ___________________<br>
                        <span style="font-size:9;">{{$apb}}</span>
                    </p>
                </div>
                <div style="width: 5%;display: table-cell;"></div>                
                <div style="width: 17.05%;display: table-cell;">
                    <p style=";">VERIFIED BY:<br>
                        _________________<br>
                        <br>
                        <br>
                        POSTED BY:<br>
                        _________________
                    </p>
                </div>
                <div style="width: 1%;display: table-cell;"></div>                
                <div style="width: 17.05%;display: table-cell;">
                    <p >RECEIVED BY:<br>
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
                    <div style="width: 30%;display: table-cell;">
                        <p >**FILENAME: &nbsp;D:\DOLE{{str_replace("-","",$acicno)}}.txt</p>
                    </div>
                    <div style="width: 30%;display: table-cell;">
                        <p style="text-align: right;"><strong>** FOR LBP USE ONLY **</strong><br>
                            <span>Page 1 of 2</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- LBP P2 Header -->
        <div class="page-break">
            <div class="container" style="width: 100%; display:table;">
                <div style="width: 30%;display: table-cell;">
                    <p >
                    </p>
                </div>
                <div style="width: 43%;display: table-cell;">
                    <p style="text-align: center;">LANDBANK OF THE PHILIPPINES<br>
                        C. M. RECTO<br>
                        C. M. RECTO, DAVAO CITY<br>
                        {{ \Carbon\Carbon::now()->format('n/j/Y') }}<br>
                        <strong>ADVICE OF CHECKS ISSUED AND CANCELLED</strong><br>
                        REPORT SUMMARY
                    </p>
                </div>                
                <div style="width: 27%;display: table-cell;">
                    <p >
                    </p>
                </div>  
            </div>
            <div class="container" style="width: 100%; display:table;">
                <table>
                    <tr>
                        <td colspan="3" style="border-top:none; border-left:none; border-right:none;"></td>
                    </tr>
                    <tr>
                        <td style="white-space: nowrap; border: none;  width:19.35%; padding: 1px;">NUMBER OF ACIC(S): &nbsp;</td>
                        <td style=" border: none;  width:77%; padding: 1px;">1</td>
                        <td style=" border-left: none; border-top: none; border-right: none;  width:2.68%; padding: 0px;"></td>                        
                    </tr>
                    <tr>
                        <td style=" white-space: nowrap; border: none;  width:19.35%; padding: 1px;">GRAND TOTAL :&nbsp;</td>
                        <td style=" border: none;  width:77%; padding: 1px;">{{number_format($sum,2)}}</td>                        
                    </tr>
                    <tr>
                        <td style=" border-left: none; border-top: none; border-right: none; white-space: nowrap; width:19.35%; padding: 0px;">AMOUNT IN WORDS :<br> <span style="color:white;">placeholder</span></td>
                        <td style=" border-left: none; border-top: none; border-right: none;  width:77%; padding: 0px;">{{$inwords}}</td>
                        <td style=" border-left: none; border-top: none; border-right: none;  width:2.68%; padding: 0px;"></td>
                    </tr>
                </table>
            </div>
            <div class="container" style="width: 100%; display:table;">
                <div style="width: 22.80%;display: table-cell;">
                    <p style="white-space: nowrap; left: 3;">CERTIFIED CORRECT BY:<br>
                        ___________________<br>
                        <span style="font-size: 9;">{{$ccb}}</span><br>
                        <br>
                        APPROVED BY:<br>
                        ___________________<br>
                        <span style="font-size:9;">{{$apb}}</span>
                    </p>
                </div>
                <div style="width: 5%; display: table-cell;"></div>                
                <div style="width: 17.05%; display: table-cell;">
                    <p style=";">VERIFIED BY:<br>
                        _________________<br>
                        <br>
                        <br>
                        POSTED BY:<br>
                        _________________
                    </p>
                </div>
                <div style="width: 1%; display: table-cell;"></div>                
                <div style="width: 17.05%; display: table-cell;">
                    <p >RECEIVED BY:<br>
                        _________________<br>
                        <br>
                        <br>
                        DELIVERED BY:<br>
                        _________________
                    </p>
                </div>
                <div style="width: 37.1%;display: table-cell;"></div>                
            </div>
            <div class="container" style="width: 100%; display:table;">
                <table>
                    <tr>
                        <td colspan="3" style="border-top:none; border-left:none; border-right:none;"></td>
                    </tr>
                    <tr>
                        <td style=" white-space: nowrap; border: none;  width:20%; padding: 1px;">** <strong>FOR LBP USE ONLY</strong></td>
                        <td style="text-align:right; border: none;  width:75%; padding: 1px;">**</td>
                        <td style="text-align:right; border: none;  width:75%; padding: 1px;"></td>                                                
                    </tr>
                    <tr>
                        <td style=" white-space: nowrap;border-left: none; border-top: none; border-right: none;  width:20%; padding: 1px;">** <strong>HASH TOTAL:</strong></td>
                        <td style="text-align:right; border-left: none; border-top: none; border-right: none;  width:; padding: 1px;">{{number_format($hash_total,2)}} &nbsp;**</td>    
                        <td style="text-align:right; border: none;  width:; padding: 1px;">Page 2 of 2</td>                                            
                    </tr>
                </table>
            </div>
        </div>
        <!-- BTR P1 Header -->
        <div class="page-break">
            <div class="container" style="width: 100%; display:table;">
                <div style="width: 27%;display: table-cell;">
                    <p style="white-space: nowrap;">LAND BANK OF THE PHILIPPINES<br>
                    C. M. RECTO<br>
                    C. M. RECTO, DAVAO CITY<br>
                    DATE PREPARED {{ \Carbon\Carbon::now()->format('n/j/Y') }}
                    </p>
                </div>
                <div style="width: 47%;display: table-cell;">
                    <p style="text-align: center;">DEPARTMENT OF LABOR AND EMPLOYMENT
                    QUIMPO BOULEVARD, BRGY. 74-A MATINA
                    CROSSING, DAVAO CITY</p>
                </div>                
                <div style="width: 23%;display: table-cell;">
                    <p style="white-space: nowrap;">ACIC NO.: <br>
                    ORG CODE:<br>
                    FUNDING SOURCE:<br>
                    AREA CODE:<br>
                    ALLOCATION NO : 
                    </p>
                </div><div style="width: 2%;display: table-cell;">
                    <p style="text-align:right;">{{$acicno}}<br>
                    160010300011<br>01101101<br>1190<br>{{$nca}}
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
                        <td class="column-title" style="width:11.29%;">CHECK NO.</td>
                        <td class="column-title" style="white-space: nowrap; width:14.52%;">DATE OF ISSUE</td>
                        <td class="column-title" style="width:33.87%;">PAYEE</td>
                        <td class="column-title" style="width:17.74%;">AMOUNT</td>
                        <td class="column-title" style="width:11.29%;">OBJ CODE</td>
                        <td class="column-title" style="width:9.68%">REMARKS</td>
                    </tr>
                    @foreach ($acics as $acic)
                        <tr>
                            <td style="text-align:right;">{{ str_pad($acic->check_number,10,'0', STR_PAD_LEFT)}} </td>
                            <td>{{ preg_replace('/(\d{1,2})\/(\d)\/(\d{4})/', '$1/0$2/$3',$acic->check_date)}} </td>
                            <td>{{ substr($acic->payee,0,40)}} </td>
                            <td style="text-align: right;">{{ number_format(($acic->amount),2) }} </td>
                            <td style="text-align:center;">{{ $acic->uacs}} </td>
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
                        <td style=" white-space: nowrap; border: none;  width:21%; padding: 1px;">TOTAL ACIC AMOUNT :&nbsp;</td>
                        <td style=" border: none;  width:51%; padding: 1px;">&nbsp;{{number_format($sum,2)}}</td>                        
                        <td style=" white-space: nowrap; border: none;  width:19%; padding: 1px;">TOTAL NO. OF CHECKS :</td>
                        <td style=" border: none;  width:9%; padding: 1px;">&nbsp;{{$acics->count()}}</td>
                    </tr>
                </tbody>
            </table>
            <table>
                <tbody>
                    <tr>
                        <td style=" border: none;  width:21%; padding: 0px; display:flex; white-space: nowrap;">AMOUNT IN WORDS :</td>
                        <td style=" border: none;  width:79%; padding: 0px; ">{{$inwords}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="prepared">
            <div class="container" style="width: 100%; display:table; justify-content: space-between;">
                <div style="width: 36.10%;display: table-cell;"><br>
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
                <div style="width: 1%;display: table-cell;"></div>
                <div style="width: 22.80%;display: table-cell;">
                    <p style="white-space: nowrap; left: 3;">CERTIFIED CORRECT BY:<br>
                        ___________________<br>
                        <span style="font-size: 9;">{{$ccb}}</span><br>
                        <br>
                        APPROVED BY:<br>
                        ___________________<br>
                        <span style="font-size:9;">{{$apb}}</span>
                    </p>
                </div>
                <div style="width: 5%;display: table-cell;"></div>                
                <div style="width: 17.05%;display: table-cell;">
                    <p style=";">VERIFIED BY:<br>
                        _________________<br>
                        <br>
                        <br>
                        POSTED BY:<br>
                        _________________
                    </p>
                </div>
                <div style="width: 1%;display: table-cell;"></div>                
                <div style="width: 17.05%;display: table-cell;">
                    <p >RECEIVED BY:<br>
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
                    <div style="width: 30%;display: table-cell;">
                        <p >**FILENAME: &nbsp;D:\DOLE{{str_replace("-","",$acicno)}}BTR.txt</p>
                    </div>
                    <div style="width: 30%;display: table-cell;">
                        <p style="text-align: right;"><strong>** FOR BTR USE ONLY **</strong><br>
                            <span>Page 1 of 2</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-break">
            <div class="container" style="width: 100%; display:table;">
                <div style="width: 30%;display: table-cell;">
                    <p >
                    </p>
                </div>
                <div style="width: 43%;display: table-cell;">
                    <p style="text-align: center;">LANDBANK OF THE PHILIPPINES<br>
                        C. M. RECTO<br>
                        C. M. RECTO, DAVAO CITY<br>
                        {{ \Carbon\Carbon::now()->format('n/j/Y') }}<br>
                        <strong>ADVICE OF CHECKS ISSUED AND CANCELLED</strong><br>
                        REPORT SUMMARY
                    </p>
                </div>                
                <div style="width: 27%;display: table-cell;">
                    <p >
                    </p>
                </div>  
            </div>
            <div class="container" style="width: 100%; display:table;">
                <table>
                    <tr>
                        <td colspan="3" style="border-top:none; border-left:none; border-right:none;"></td>
                    </tr>
                    <tr>
                        <td style=" white-space: nowrap; border: none;  width:19.35%; padding: 1px;">NUMBER OF ACIC(S): &nbsp;</td>
                        <td style=" border: none;  width:77%; padding: 1px;">1</td>
                        <td style=" border-left: none; border-top: none; border-right: none;  width:2.68%; padding: 0px;"></td>                        
                    </tr>
                    <tr>
                        <td style=" white-space: nowrap; border: none;  width:19.35%; padding: 1px;">GRAND TOTAL :&nbsp;</td>
                        <td style=" border: none;  width:77%; padding: 1px;">{{number_format($sum,2)}}</td>                        
                    </tr>
                    <tr>
                        <td style=" border-left: none; border-top: none; border-right: none; white-space: nowrap; width:19.35%; padding: 0px;">AMOUNT IN WORDS :<br> <span style="color:white;">placeholder</span></td>
                        <td style=" border-left: none; border-top: none; border-right: none;  width:77%; padding: 0px;">{{$inwords}}</td>
                        <td style=" border-left: none; border-top: none; border-right: none;  width:2.68%; padding: 0px;"></td>
                    </tr>
                </table>
            </div>
            <div class="container" style="width: 100%; display:table;">
                <div style="width: 22.80%;display: table-cell;">
                    <p style="white-space: nowrap; left: 3;">CERTIFIED CORRECT BY:<br>
                        ___________________<br>
                        <span style="font-size: 9;">{{$ccb}}</span><br>
                        <br>
                        APPROVED BY:<br>
                        ___________________<br>
                        <span style="font-size:9;">{{$apb}}</span>
                    </p>
                </div>
                <div style="width: 5%;display: table-cell;"></div>                
                <div style="width: 17.05%;display: table-cell;">
                    <p style=";">VERIFIED BY:<br>
                        _________________<br>
                        <br>
                        <br>
                        POSTED BY:<br>
                        _________________
                    </p>
                </div>
                <div style="width: 1%;display: table-cell;"></div>                
                <div style="width: 17.05%;display: table-cell;">
                    <p >RECEIVED BY:<br>
                        _________________<br>
                        <br>
                        <br>
                        DELIVERED BY:<br>
                        _________________
                    </p>
                </div>
                <div style="width: 37.1%;display: table-cell;"></div>                
            </div>
            <div class="container" style="width: 100%; display:table;">
                <table>
                    <tr>
                        <td colspan="2" style="border-top:none; border-left:none; border-right:none;"></td>
                    </tr>
                    <tr>
                        <td style=" white-space: nowrap;border-left: none; border-top: none; border-right: none;  width:20%; padding: 3px;">**<strong>FOR BTR USE ONLY</strong></td>
                        <td style="text-align:right; border-left: none; border-top: none; border-right: none;  width:; padding: 3px;"></td>    
                        <td style="text-align:right; border: none;  width:; padding: 3px;">Page 2 of 2</td>                                            
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>
