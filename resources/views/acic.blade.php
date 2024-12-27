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
                        <form action="/importacic/pdf" method="post" enctype="multipart/form-data">
                        @csrf
                            <label for="acicno">ACIC NO.:</label>
                            <input type="text" name="acicno" style="color:black;" 
                            maxlength="9" pattern="\d{2}-\d{2}-\d{3}" placeholder="##-##-###" required>
                            <label for="nca">NCA:</label>
                            <input type="text" name="nca" placeholder ="######-#" style="color:black;"
                            maxlength="8" pattern="\d{6}-\d{1}" required>
                            <label for="request">Generate</label>
                            <select style="color:black;" name="request" required>
                                <option value="" disabled>Choose</option>
                                <option value="pdf">PDF</option>
                                <option value="lbp">LBP Text File</option>
                                <option value="btr">BTR Text File</option>
                            </select>
                            <button button type="submit" class="xx">Generate</button>
                            <a href="/importacic/del"><button button type="button" class="xx">Clear Contents</button></a>
                        </form>
                        @csrf
                        </form>
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
