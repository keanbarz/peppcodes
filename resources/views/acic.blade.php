<x-app-layout>

    <div class="py-4">
        <div class=" mx-auto sm:px-6 lg:px-8 py-4">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (Auth::user()->field_office !== "")
                        <p>Experimental, for Admin only.</p>
                    @else                        
                        <form action="{{ route('importacic') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                            <input type="file" name="file" required>
                            <button type="submit" class="xx">Upload</button>
                        </form>   
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
