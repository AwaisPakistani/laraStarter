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
                            <h3>Permissions</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Permissions</li>
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
                                    Permissions List
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('permissions.create') }}" class="btn btn-primary btn-outline">
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
                               perPageRoute="{{ route('permissions.index') }}"
                               />
                                {{-- Your existing search component --}}
                                <x-search-record searchRoute="{{ route('permissions.index') }}" />
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
                                    @forelse ($allRecords as $Permission)
                                    <tr>
                                        <td>
                                         {{ $loop->iteration + $allRecords->firstItem() - 1 }}</td>
                                        <td>{{$Permission->name}}</td>
                                        <td>
                                            {{--@statusBadge($Permission->status)--}}
                                            <span id="status_change" data-id="{{$Permission->id}}" data-name="permissions">
                                            @toggleStatusStatic($Permission->status)
                                            </span>
                                        </td>
                                        <td>
                                             <x-action-buttons
                                            :canEdit="auth()->user()->hasPermission('permissions.edit')" :canDelete="auth()->user()->hasPermission('permissions.destroy')" :canShow="auth()->user()->hasPermission('permissions.show')" :editRoute="route('permissions.edit',$Permission)" :deleteRoute="route('permissions.destroy',$Permission)" :showRoute="route('permissions.show',$Permission)"
                                            />
                                        </td>
                                    </tr>
                                    @empty
                                    Data Not Found
                                    @endforelse

                                </tbody>
                            </table>
                             <div class="row">
                                <div class="col-md-12 text-right">
                                {{ $allRecords->withQueryString()->links() }}
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
@stop
@endsection
