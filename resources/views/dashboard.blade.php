<x-app-layout>
    <script>
        $(document).ready(function () {
            // Function to reload the page with query parameters for filtering
            function filter() {
                var year = $('#year').val();
                var field = $('#field').val();

                // Reload the page with updated query parameters
                var url = new URL(window.location.href);
                url.searchParams.set('year', year);
                url.searchParams.set('field', field);
                window.location.href = url.toString();
            }

            // Trigger the filter function on input changes
            $('#year').on('change', function () {
                filter();
            });

            $('#field').on('change', function () {
                filter();
            });
        });
    </script>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table>
                        <thead>
                            <tr>
                                <th colspan="5" class="text-center">
                                    @if (Auth::user()->field_office == "" || Auth::user()->field == "demo")
                                    <label for="field">Field Office:</label>
                                    <select style="color:black;" id="field" name="field">
                                        <option value="" {{ $fv === 'all' ? '' : 'selected' }}>All</option>
                                        <option value="roxi" {{ $fv === 'roxi' ? 'selected' : '' }}>ROXI </option>
                                        <option value="dcfo" {{ $fv === 'dcfo' ? 'selected' : '' }}>DCFO</option>
                                        <option value="dsfo" {{ $fv === 'dsfo' ? 'selected' : '' }}>DSFO</option>
                                        <option value="docfo" {{ $fv === 'docfo' ? 'selected' : '' }}>DOCFO</option>
                                        <option value="dnfo" {{ $fv === 'dnfo' ? 'selected' : '' }}>DNFO</option>
                                        <option value="dieo" {{ $fv === 'dieo' ? 'selected' : '' }}>DIEO</option>
                                        <option value="dorfo" {{ $fv === 'dorfo' ? 'selected' : '' }}>DORFO</option>
                                        <option value="dofo" {{ $fv === 'dofo' ? 'selected' : '' }}>DOFO</option>
                                    </select>
                                    @else
                                    {{strtoupper(Auth::user()->field_office)}}
                                    @endif
                                    <label for="year">Year:</label>
                                    <select style="color:black;" id="year" name="year">
                                        <option value="" {{ $cy == 'all' ? '' : 'selected' }}>All</option>
                                        @foreach ($years as $year)
                                                <option value="{{$year}}" {{ $cy == $year ? 'selected' : '' }}>{{$year}}</option>
                                        @endforeach
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center">PROGRAM</th>
                                <th class="text-center">CLAIMED</th>
                                <th class="text-center">UNCLAIMED</th>
                                <th class="text-center">CANCELLED</th>
                                <th class="text-center">TOTAL</th>
                            </tr>
                        </thead>
                            <tbody id="search-results">
                                <tr>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">GIP</td>
                                    @php
                                        $gpcsum = 0;
                                        $gpusum = 0;
                                        $gpcasum =0;
                                    @endphp
                                    @foreach ($peppsum as $sum)
                                        @if (str_contains($sum->sender, 'GIP') && $sum->status === 'Claimed')
                                            @php
                                                $gpcsum += $sum->principal;
                                            @endphp
                                        @elseif (str_contains($sum->sender, 'GIP') && $sum->status === 'Unclaimed')
                                            @php
                                                $gpusum += $sum->principal;
                                            @endphp
                                        @elseif (str_contains($sum->sender, 'GIP') && $sum->status === 'Cancelled')
                                            @php
                                                $gpcasum += $sum->principal;
                                            @endphp
                                        @endif
                                    @endforeach
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format($gpcsum,2)}}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format($gpusum,2)}}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format($gpcasum,2)}}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format(($gpcsum+$gpusum+$gpcasum),2)}}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">TUPAD</td>
                                    @php
                                        $tpcsum = 0;
                                        $tpusum = 0;
                                        $tpcasum =0;
                                    @endphp
                                    @foreach ($peppsum as $sum)
                                        @if (str_contains($sum->sender, 'TUPAD') && $sum->status === 'Claimed')
                                            @php
                                                $tpcsum += $sum->principal;
                                            @endphp
                                        @elseif (str_contains($sum->sender, 'TUPAD') && $sum->status === 'Unclaimed')
                                            @php
                                                $tpusum += $sum->principal;
                                            @endphp
                                        @elseif (str_contains($sum->sender, 'TUPAD') && $sum->status === 'Cancelled')
                                            @php
                                                $tpcasum += $sum->principal;
                                            @endphp
                                        @endif
                                    @endforeach
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format($tpcsum,2)}}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format($tpusum,2)}}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format($tpcasum,2)}}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format(($tpcsum+$tpusum+$tpcasum),2)}}</td>                                    
                                </tr>    
                                <tr>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">SPES</td>
                                    @php
                                        $spcsum = 0;
                                        $spusum = 0;
                                        $spcasum =0;
                                    @endphp
                                    @foreach ($peppsum as $sum)
                                        @if (str_contains($sum->sender, 'SPES') && $sum->status === 'Claimed')
                                            @php
                                                $spcsum += $sum->principal;
                                            @endphp
                                        @elseif (str_contains($sum->sender, 'SPES') && $sum->status === 'Unclaimed')
                                            @php
                                                $spusum += $sum->principal;
                                            @endphp
                                        @elseif (str_contains($sum->sender, 'SPES') && $sum->status === 'Cancelled')
                                            @php
                                                $spcasum += $sum->principal;
                                            @endphp
                                        @endif
                                    @endforeach
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format($spcsum,2)}}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format($spusum,2)}}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format($spcasum,2)}}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format(($spcsum+$spusum+$spcasum),2)}}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">TOTAL</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format(($gpcsum+$tpcsum+$spcsum),2)}}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format(($gpusum+$tpusum+$spusum),2)}}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format(($gpcasum+$tpcasum+$spcasum),2)}}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format(($peppsum->sum('principal')),2)}}</td>
                                </tr>            
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
