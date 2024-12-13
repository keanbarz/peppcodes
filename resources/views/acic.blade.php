<x-app-layout>

    <div class="py-4">
        <div class=" mx-auto sm:px-6 lg:px-8 py-4"><!--max-w-7xl-->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (Auth::user()->field_office !== "")
                        <p>Experimental, for Admin only.</p>
                    @else
                        <form action="{{ route('importacic') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                            <input type="file" name="file">
                            <button type="submit" class="xx">Upload</button>
                            @if (!empty($acics))
                            <a href="/importacic/pdf"><button button type="button" class="xx">Generate PDF</button></a>
                            @endif
                        </form>
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
