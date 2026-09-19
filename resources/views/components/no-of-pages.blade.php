@props([
    'perPageRoute',
])
<div class="d-flex align-items-center">
    <label for="perPage" class="me-2">Show:</label>
        <select name="perPage" id="perPage" class="form-select" style="width: auto;" onchange="window.location.href='{{ $perPageRoute }}?perPage='+this.value+'&search={{ request('search') }}'">
            {{-- <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5</option> --}}
            <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
            <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
        </select>
</div>
