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
            
        @page :first {
            margin: 0in 0.4in 0.5in 0.4in;
        }

        @page {
            margin: 0.4in 0.4in 0.5in 0.4in;
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
            content: "Page " counter(page) " of {{$totalPages}}";
        }
        </style>
    </head>
    <body>
        <img src="{{ public_path('images/head.png') }}" alt="Header Image" style="width:100%; ">
        @foreach ($pages as $page => $peppcodes)
            @if ($page > 0)
                <div class="page-break"></div>
            @endif
            <table>
                <thead>
                    <tr>
                        @if ($field != '' || $program != '')
                        <th colspan="5" class="text-center">List of {{$status}} Transactions for the Year {{$year}} - {{$field}} {{$program}}</th>
                        @elseif ($field != '' || $program != '')
                        <th colspan="5" class="text-center">List of {{$status}} Transactions for the Year {{$year}} - {{$field}}{{$program}}</th>
                        @else
                        <th colspan="5" class="text-center">List of {{$status}} Transactions for the Year {{$year}}</th>
                        @endif
                    </tr>
                    <tr>
                        <th class="text-center">TRANSACTION DATE</th>
                        <th class="text-center">TRANSACTION CODE</th>
                        <th class="text-center">SENDER</th>
                        <th class="text-center">RECEIVER</th>
                        <th class="text-center">PRINCIPAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($peppcodes as $peppcode)
                        <tr>
                            <td>{{ $peppcode->tranx_date}} </td>
                            <td>{{ $peppcode->tranx_code}} </td>
                            <td>{{ $peppcode->sender}} </td>
                            <td>{{ $peppcode->receiver}} </td>
                            <td style="text-align: right;">{{ number_format(intval($peppcode->principal),2) }} </td>
                        </tr>
                    @endforeach
                    @if($loop->last)
                    <tr>
                        <th colspan="2" class="text-center">TOTAL PAX: {{$count}}</th>
                        <th colspan="3" class="text-center">TOTAL AMOUNT: {{ number_format($sum,2) }}</th>
                    </tr>
                    @endif
                </tbody>
            </table>
            @if ($loop->last)
            <div class="prepared">Prepared By: {{Auth::user()->name}}<br>
            <span style="font-style:italic; font-size:9;">Electronically Generated. No signature required.</span>
            </div>
            @endif
            <div class="footer">
            <span class="page-number"></span>
        </div>
        @endforeach
    </body>
</html>
