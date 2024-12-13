<x-app-layout>
    <style>
        .xx {
            background-color: green;
            color: white;
            min-width: 80px;
            height: 40px;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 10px;
            }
    </style>

    <div class="py-12">
        <div class="sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                <form action="{{ route('export') }}" method="get" enctype="multipart/form-data">
                    <button type="submit" class="xx">Export Unclaimed</button>
                </form>           
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
