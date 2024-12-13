<!DOCTYPE html>
<html>
    <head>
        <title>List of {{$status}} Transactions for the Year {{$year}}</title>
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
            
        @page {
            margin: 0.5in 0.5in 0.5in 0.5in;
        }
        .page-break {
            page-break-before: always;
        }
        .footer {
            position: absolute;
            bottom: -20;
            width: 100%;
            text-align: center;
            /*border-top: 1px solid black;*/
            padding: 5px;
            /*font-size: 12px;*/
        }

        .page-number::before {
            content: "Page " counter(page) " of {{$pages}}";
        }
        </style>
    </head>
    <body>
        <!--h1>HEADER CHECK</h1-->
        <table>
            <thead>
                <tr>
                    <th colspan="5" class="text-center">List of {{$status}} Transactions for the Year {{$year}}</th>
                </tr>
                <tr>
                    <th class="text-center">TRANSACTION DATE</th>
                    <th class="text-center">TRANSACTION CODE</th>
                    <th class="text-center">SENDER</th>
                    <th class="text-center">RECEIVER</th>
                    <th class="text-center">PRINCIPAL</th>
                </tr>
            </thead>
            <tbody id="search-results">
                @foreach ($peppcodes as $peppcode)
                    <tr>
                        <td>{{ $peppcode->tranx_date}} </td>
                        <td>{{ $peppcode->tranx_code}} </td>
                        <td>{{ $peppcode->sender}} </td>
                        <td>{{ $peppcode->receiver}} </td>
                        <td style="text-align: right;">{{ number_format(intval($peppcode->principal),2) }} </td>
                    </tr>
                    @if ($loop->last)
                        <tr>
                            <th colspan="4" class="text-center">TOTAL</th>
                            <th class="text-left">{{ number_format($sum,2) }}</th>
                        </tr>
            </tbody>
        </table>
                    @endif
                    @if ($loop->last)
                        <div class="footer">
                            <span class="page-number"></span>
                        </div>
                    @else
                    <div class="footer">
                        <span class="page-number"></span>
                    </div>
                     @endif   
                @endforeach
    </body>
</html>
