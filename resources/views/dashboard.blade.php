<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}

                   <h2>Import Sales Excel File</h2> @if(session('success')) <p style="color: green;"> {{ session('success') }} </p> @endif @if($errors->any()) <div style="color: red;"> @foreach($errors->all() as $error) <p>{{ $error }}</p> @endforeach </div> @endif 
                   <form action="{{ url('/sales/import') }}" method="POST" enctype="multipart/form-data" > @csrf <div> <label for="file">Select Excel File:</label> <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv" required > </div> <br> <button class="btn btn-success" type="submit"> Import Customers </button> </form>

                    </form><br>

                   <!-- Trigger Export Link -->
                    <a class="btn btn-info" href="{{ route('sales.export') }}">
                        Export Sales
                    </a>

                    <!-- Session Alert with Download Link -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            {{ session('success') }}
                            
                            @if (session('export_file'))
                                <!-- Calls sales.download with the filename parameter -->
                                <a href="{{ route('sales.download', ['filename' => session('export_file')]) }}" class="btn btn-sm btn-dark ms-2">
                                    Download Spreadsheet
                                </a>
                            @endif
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger mt-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
