<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(Auth::user()->field_office != "final")
                    <p>In Progress... Test sum cancelled {{number_format($peppsum->where('sender', 'like' , '%' . 'gip' . '%')->where('status', 'cancelled')->sum('principal'),2)}}</p>
                    @else
                    {{ __("To-DO: Summary sa Claimed, Unclaimed, and Cancelled per FO and Program") }}
                    <table>
                        <thead>
                            <tr>
                                <th colspan="4" class="text-center">DNFO</th>
                            </tr>
                            <tr>
                                <th class="text-center">PROGRAM</th>
                                <th class="text-center">CLAIMED</th>
                                <th class="text-center">UNCLAIMED</th>
                                <th class="text-center">CANCELLED</th>
                            </tr>
                        </thead>
                            <tbody id="search-results">
                                <tr>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">GIP</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format($peppsum->where('sender', 'like' , '%' . 'gip' . '%')->where('status', 'claimed')->sum('principal'),2)}}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format($peppsum->where('sender', 'like' , '%' . 'gip' . '%')->where('status', 'unclaimed')->sum('principal'),2)}}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{number_format($peppsum->where('sender', 'like' , '%' . 'gip' . '%')->where('status', 'cancelled')->sum('principal'),2)}}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">TUPAD</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"></td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"></td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"></td>
                                </tr>    
                                <tr>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">SPES</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"></td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"></td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">TOTAL</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"></td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"></td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"></td>
                                </tr>            
                            </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
