<x-app-layout>

    <script>
        $(document).ready(function () {
            // Function to perform the AJAX request and update product list
            function filterProducts(page = 1) {
                var search = $('#search').val();
                var status = $('#status').val();
                var month = $('#month').val();
                var year = $('#year').val();
                var field = $('#field').val();
                var program = $('#program').val();

                $.ajax({
                    url: '/peppcodes/filter',
                    method: 'GET',
                    data: {
                        search: search,
                        page: page,
                        status: status,
                        year: year,
                        field: field,
                        program: program,
                        month: month
                    },
                    success: function (response) {
                        var queriesHtml = '';
                        response.results.forEach(function (result) {
                            // Create a number formatter instance
                            let formatter = new Intl.NumberFormat(undefined, {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });

                            queriesHtml += '<tr>';
                            queriesHtml += '<td style="border: 1px solid #ddd; padding: 8px; text-align: left;">' + result.tranx_date + '</td>';
                            queriesHtml += '<td style="border: 1px solid #ddd; padding: 8px; text-align: left;">' + result.status + '</td>';
                            queriesHtml += '<td style="border: 1px solid #ddd; padding: 8px; text-align: left;">' + result.tranx_code + '</td>';
                            queriesHtml += '<td style="border: 1px solid #ddd; padding: 8px; text-align: left;">' + result.sender + '</td>';
                            queriesHtml += '<td style="border: 1px solid #ddd; padding: 8px; text-align: left;">' + result.receiver + '</td>';
                            queriesHtml += '<td style="border: 1px solid #ddd; padding: 8px; text-align: right;">' + formatter.format(result.principal) + '</td>';
                            queriesHtml += '</tr>';
                        });

                        $('#search-results').html(queriesHtml);
                        $('#pagination-links').html(response.pagination);

                        $('#pagination-links a').on('click', function (e) {
                            e.preventDefault();
                            var page = $(this).attr('href').split('page=')[1];
                            filterProducts(page);
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error: ' + status + ' - ' + error);
                    }
                });
            }

            $('#status').on('change', function () {
                filterProducts(); // Call filter function on any input change
            });
            // Event listeners for input fields to trigger live filtering
            $('#search').on('input', function () {
                filterProducts(); // Call filter function on any input change
            });
            $('#year').on('change', function () {
                filterProducts(); // Call filter function on any input change
            });
            $('#field').on('change', function () {
                filterProducts(); // Call filter function on any input change
            });
            $('#program').on('change', function () {
                filterProducts(); // Call filter function on any input change
            });
            $('#month').on('change', function () {
                filterProducts(); // Call filter function on any input change
            });
            
            // Initial filtering on page load (optional)
            filterProducts();
        });
    </script>

    <div class="py-4">
        <div class=" mx-auto sm:px-6 lg:px-8"><!--max-w-7xl-->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                @if (Auth::user()->field_office == "guest")
                <p>Please contact admin for access.</p>
                @else
                <form method="GET" action="/peppcodes/generate-pdf" id="generate" target="_blank">
                @if (Auth::user()->field_office == "")
                <a href="/peppcodes/update"><button type="button" id="update" class="xx">Upload</button></a>
                <a href="/peppcodes/notify"><button type="button" id="notify" class="xx">Notify</button></a>
                @endif
                <button type="submit" class="xx">Generate Report</button>
                <label for="search">Search:</label>
                <input type="text" id="search" size="35" style="color:black;" placeholder="Search by name">
                <label for="status">Status:</label>
                <select style="color:black;" id="status" name="status">
                    <option value="" selected>All</option>
                    <option value="claimed">Claimed</option>
                    <option value="unclaimed" selected>Unclaimed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <label for="month">Month:</label>
                <select style="color:black;" id="month" name="month">
                    <option value="" selected>All</option>
                    @foreach ($months as $num => $name)
                        <option value="{{ str_pad($num,2,'0', STR_PAD_LEFT) }}">{{ $name }}</option>
                    @endforeach
                </select>
                <label for="year">Year:</label>
                <select style="color:black;" id="year" name="year">
                    <option value="" selected>All</option>
                    @foreach ($years as $year)
                        @php
                            $currentYear = date('Y');
                        @endphp
                        @if ($currentYear == $year)
                            <option value="{{$year}}" selected>{{$year}}</option>
                        @else
                            <option value="{{$year}}">{{$year}}</option>
                        @endif
                    @endforeach
                </select>
                <label for="program">Program:</label>
                <select style="color:black;" id="program" name="program">
                    <option value="" selected>All</option>
                    <option value="gip" >GIP</option>
                    <option value="tupad" >TUPAD</option>
                    <option value="spes" >SPES</option>
                </select>
                @if (Auth::user()->field_office == "" || Auth::user()->field_office == "demo")
                <label for="field">Field Office:</label>
                <select style="color:black;" id="field" name="field">
                    <option value="" selected>All</option>
                    <option value="roxi" >ROXI </option>
                    <option value="dcfo" >DCFO</option>
                    <option value="dsfo" >DSFO</option>
                    <option value="docfo" >DOCFO</option>
                    <option value="dnfo" >DNFO</option>
                    <option value="dieo" >DIEO</option>
                    <option value="dorfo" >DORFO</option>
                    <option value="dofo" >DOFO</option>
                </select>
                @endif
                <script>
                    document.getElementById('generate').addEventListener('submit', function(e) {
                        e.preventDefault(); // Prevent the form from submitting normally

                        let newTab = window.open('', '_blank'); // Open the new tab

                        // Gather form data
                        let form = document.getElementById('generate');
                        let formData = new URLSearchParams(new FormData(form)).toString(); // Convert form data to query string
                        
                        // Send the GET request with query parameters
                        fetch('/peppcodes/generate-pdf?' + formData, {
                            method: 'GET'
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('No data to generate');
                            }
                            return response.blob(); // Get the file as a blob if data exists
                        })
                        .then(blob => {
                            let url = window.URL.createObjectURL(blob);
                            newTab.location.href = url; // Set the blob URL in the new tab
                        })
                        .catch(error => {
                            newTab.close(); // Close the new tab if no data
                            alert(error.message); // Display the error message
                        });
                    });
                </script>
                </form>
                </div>
            </div>
        </div>
        <div class=" mx-auto sm:px-6 lg:px-8 py-4"><!--max-w-7xl-->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($forcancel->isnotempty())
                    <table>
                        <thead>
                                <tr>
                                    <th colspan="6" class="text-center">Transactions Nearing Cancellation (60 Days)</th>
                                </tr>
                                <tr>
                                    <th class="text-center">TRANX_DATE</th>
                                    <th class="text-center">TRANX_CODE</th>
                                    <th class="text-center">SENDER</th>
                                    <th class="text-center">RECEIVER</th>
                                    <th class="text-center">PRINCIPAL</th>
                                    <th class="text-center">DAYS ELAPSED</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($forcancel as $cancel)
                                    @php
                                    $tranxDate = \Carbon\Carbon::createFromFormat('m-d-Y', $cancel->tranx_date);
                                    $daysElapsed = $tranxDate->diffInDays(\Carbon\Carbon::now());
                                    @endphp
                                    <tr>
                                        <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{$cancel->tranx_date}}</td>
                                        <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{$cancel->tranx_code}}</td>
                                        <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{$cancel->sender}}</td>
                                        <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{$cancel->receiver}}</td>
                                        <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format($cancel->principal,2)}}</td>
                                        <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format($daysElapsed,0)}} Days</td>
                                    </tr>
                                @endforeach
                            </tbody>
                    </table></br></br>
                    @endif
                    <table>
                        <thead>
                            <tr>
                                <th class="text-center">TRANX_DATE</th>
                                <th class="text-center">STATUS</th>
                                <th class="text-center">TRANX_CODE</th>
                                <th class="text-center">SENDER</th>
                                <th class="text-center">RECEIVER</th>
                                <th class="text-center">PRINCIPAL</th>
                            </tr>
                        </thead>
                        <tbody id="search-results">
                            <!--JQUERIED-->    
                        </tbody>
                    </table></br>
                    <div id="pagination-links" class="d-flex justify-content-center">
                </div>
            </div>
        </div>
    </div>
    @endif
</x-app-layout>
