<x-admin.panels.page>

    @if (session()->has('success'))
        <x-admin.html.alert-message bg="s">
            {{ session('success') }}
        </x-admin.html.alert-message>
    @endif
    @if (session()->has('error'))
        <x-admin.html.alert-message bg="d">
            {{ session('error') }}
        </x-admin.html.alert-message>

    @endif

    <form method="POST" action="{{ route('filament.pages.database-tables-export.export') }}" class="p-6 bg-white dark:bg-gray-900 shadow-md rounded-lg">
        @csrf
        <div class="row selectDiv">
            <input type="checkbox" id="select-all"
                   class="form-checkbox text-blue-600 dark:text-blue-400 bg-transparent border-gray-400 dark:border-gray-600 focus:ring-blue-500 dark:focus:ring-blue-400">
            {{ count($this->getTables())}}
        </div>

        <ul class="row db_table_name">
            @foreach($this->getTables() as $table)
                <li>

                    <input type="checkbox" name="tables[]" value="{{ $table }}"
                           class="table-checkbox form-checkbox text-blue-600 dark:text-blue-400 bg-transparent border-gray-400 dark:border-gray-600 focus:ring-blue-500 dark:focus:ring-blue-400">

                    {{ $table }}
                </li>
            @endforeach
        </ul>


        <div class="row mt-5">
            <x-filament::button type="submit" color="info">
                تصدير الجداول المحددة
            </x-filament::button>
        </div>


    </form>


    <script>
        document.getElementById('select-all').addEventListener('change', function (e) {
            document.querySelectorAll('.table-checkbox').forEach(checkbox => {
                checkbox.checked = e.target.checked;
            });
        });
    </script>

</x-admin.panels.page>
