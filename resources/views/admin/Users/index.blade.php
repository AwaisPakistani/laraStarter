@extends('layouts.master')
@section('content')
<!--style-->
@section('style')
<link rel="stylesheet" href="{{asset('assets/vendors/iconly/bold.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendors/simple-datatables/style.css')}}">
@stop
<!--/style-->
<div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Users</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Users</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-10">
                                    Users List
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-outline">
                                        <span class="bi bi-plus"></span>Create
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                        {{-- In your users/index.blade.php or similar --}}
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                {{-- Per page selector --}}
                               <x-no-of-pages
                               perPageRoute="{{ route('users.index') }}"
                               />
                                {{-- Your existing search component --}}
                                <x-search-record searchRoute="{{ route('users.index') }}" />
                            </div>
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>Sr#</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($allRecords as $User)
                                    <tr>
                                        <td>
                                         {{$loop->iteration}}</td>
                                        <td>{{$User->name}}</td>
                                        <td>
                                            {{-- @statusBadge($User->status) --}}
                                             @toggleStatus($User->status, route('users.toggleStatus', $User->id))
                                        </td>
                                        <td>
                                            {{-- <x-action-buttons
                                            :canEdit="auth()->user()->hasUser('users.edit')" :canDelete="auth()->user()->hasUser('Users.destroy')" :canShow="auth()->user()->hasUser('users.show')" :editRoute="route('users.edit',$User)" :deleteRoute="route('users.destroy',$User)" :showRoute="route('users.show',$User)"
                                            /> --}} 
                                        </td>
                                    </tr>
                                    @empty
                                    Data Not Found
                                    @endforelse

                                </tbody>
                            </table>
                             <div class="row">
                                <div class="col-md-12 text-right">
                                {{-- {{ $allRecords->withQueryString()->links() }} --}}
                                </div>
                            </div>
                        </div>
                    </div>

                </section>
</div>
@section('scripts')
<script src="{{asset('assets/vendors/simple-datatables/simple-datatables.js')}}"></script>
<script>
        // Simple Datatable
        let table1 = document.querySelector('#table1');
        let dataTable = new simpleDatatables.DataTable(table1);
</script>
<script>
    function toggleStatus(element) {
    const url = element.dataset.url;
    const isChecked = element.checked;
    alert(url); return false;
    // Optional: disable input during request to prevent double-clicks
    element.disabled = true;

    fetch(url, {
        method: 'POST', // Change to PATCH or PUT if your route expects it
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            status: isChecked ? 'active' : 'inactive' // or true/false depending on your DB
        })
    })
    .then(response => {
        element.disabled = false;
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Optional: Show a success toast/notification
        console.log('Status updated successfully:', data);
    })
    .catch(error => {
        element.disabled = false;
        // Revert the switch state if the request fails
        element.checked = !isChecked;
        alert('Failed to update status. Please try again.');
        console.error('Error:', error);
    });
}
</script>
@stop
@endsection
