<x-app-layout>

    <div class="py-4">
        <div class=" mx-auto sm:px-6 lg:px-8 py-4">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (Auth::user()->field_office !== "")
                        <p>Experimental, for Admin only.</p>
                    @else
                        @if ($acics->count() == 0)
                            <form action="{{ route('importacic') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                                <input type="file" name="file">
                                <button type="submit" class="xx">Upload</button>
                            </form>
                        @else
                        <form action="/importacic/pdf" method="post" enctype="multipart/form-data" target="_blank">
                        @csrf
                            <label for="acicno">ACIC NO.:</label>
                            <input type="text" id="acicno" name="acicno" style="color:black;" 
                            maxlength="9" pattern="\d{2}-\d{2}-\d{3}" placeholder="##-##-###" required>
                            <label for="nca">NCA:</label>
                            <input type="text" id="nca" name="nca" placeholder ="######-#" style="color:black;"
                            maxlength="8" pattern="\d{6}-\d{1}" required>
                            <label for="ncadate">NCA DATE:</label>
                            <input type="text" id="ncadate" name="ncadate" placeholder="mm/dd/yyyy" style="color:black;"
                            maxlength="10" pattern="\d{2}/\d{2}/\d{4}" required>
                            <label for="request">Generate</label>
                            <select style="color:black;" name="request" required>
                                <option value="" disabled selected>Choose</option>
                                <option value="pdf">PDF</option>
                                <option value="lbp">LBP Text File</option>
                                <option value="btr">BTR Text File</option>
                            </select>
                            <button button type="submit" class="xx">Generate</button>
                            <a href="/importacic/del"><button button type="button" class="xx">Clear Contents</button></a>
                        </form>
                        <script>
                            document.getElementById('acicno').addEventListener('input', function (e) {
                                let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
                                if (value.length > 2) value = value.slice(0, 2) + '-' + value.slice(2);
                                if (value.length > 5) value = value.slice(0, 5) + '-' + value.slice(5, 8);
                                e.target.value = value; // Update the input value
                            });
                            document.getElementById('ncadate').addEventListener('input', function (e) {
                                let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
                                if (value.length > 2) value = value.slice(0, 2) + '/' + value.slice(2);
                                if (value.length > 5) value = value.slice(0, 5) + '/' + value.slice(5, 9);
                                e.target.value = value; // Update the input value
                            });
                            document.getElementById('nca').addEventListener('input', function (e) {
                                let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
                                if (value.length > 6) value = value.slice(0, 6) + '-' + value.slice(6,7);
                                e.target.value = value; // Update the input value
                            });
                        </script>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        <div class=" mx-auto sm:px-6 lg:px-8 py-4"><!--max-w-7xl-->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table>
                        <thead>
                            <tr>
                                <th colspan="5" class="text-center">ACIC</th>
                            </tr>
                            <tr>
                                <th class="text-center">CHECK DATE</th>
                                <th class="text-center">CHECK NUMBER</th>
                                <th class="text-center">PAYEE</th>
                                <th class="text-center">UACS</th>
                                <th class="text-center">AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($acics as $acic)
                                
                                <tr>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{$acic->check_date}}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{$acic->check_number}}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{$acic->payee}}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{$acic->uacs}}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format($acic->amount,2)}}</td>
                                </tr>
                                
                            @endforeach
                        </tbody>
                    </table></br>
                    <div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
