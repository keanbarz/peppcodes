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
                border: 1px solid #ddd; /* Add border to the entire table */
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
                padding: 6px;
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
                bottom: 10;
                width: 100%;
                padding: 5px;
            }

            .page-number::before {
                content: "Page " counter(page) " of ";
            }
            .container {
            display: flex; /* Enables flexbox layout */
            justify-content: space-between; /* Distributes divs with space between them */
            gap: 10px; /* Optional: Adds spacing between the divs */
            }

            .box {
                width: 100px;
                height: 100px;
                background-color: lightblue;
                text-align: center;
                line-height: 100px; /* Centers text vertically */
                border: 1px solid #ccc;
            }
        </style>
    </head>
    <body>
        <div class="container" style="width: 100%; display: table;">
            <div class="box" style="width: 33%;">
                <p style="font-family: sans-serif; font-size: 11;">LAND BANK OF THE PHILIPPINES<br>
                C. M. RECTO<br>
                C. M. RECTO, DAVAO CITY<br>
                DATE PREAPARED CARBON MM/D/YYYY
                </p>
            </div>
            <div class="box" style="width: 33%;">
                <p style="font-family: sans-serif; font-size: 11;">DAPAT SA TUNGA NI (DEPARTMENT)
                </p>
            </div>                
            <div class="box" style="display: table-cell; width: 33%; vertical-align: top; padding: 5px; border: 1px solid #000;">
                <p style="font-family: sans-serif; font-size: 11;">KINI SA KILID (ACIC NO)
                </p>
            </div>  
        </div>
        <table>
            <thead>
                <tr>
                    <th class="text-center">CHECK NO.</th>
                    <th class="text-center">DATE OF ISSUE</th>
                    <th class="text-center">PAYEE</th>
                    <th class="text-center">AMOUNT</th>
                    <th class="text-center">OBJ CODE</th>
                    <th class="text-center">REMARKS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($acics as $acic)
                    <tr>
                        <td style="font-family: sans-serif; font-size: 11;">{{ $acic->check_number}} </td>
                        <td>{{ $acic->check_date}} </td>
                        <td>{{ $acic->payee}} </td>
                        <td style="text-align: right;">{{ number_format(intval($acic->amount),2) }} </td>
                        <td>{{ $acic->uacs}} </td>
                        <td> </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
