@extends('layout.master')
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
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
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
                                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-outline">
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
                               perPageRoute="{{ route('admin.users.index') }}"
                               />
                                {{-- Your existing search component --}}
                                <x-search-record searchRoute="{{ route('admin.users.index') }}" />
                            </div>
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>Sr#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($allRecords as $user)
                                    <tr>
                                        <td>
                                         {{$loop->iteration}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{ $user->roles[0]->name??'' }}</td>
                                        <td>
                                            @php
                                            $status=$user->status
                                            @endphp
                                            @statusBadge($status)
                                        </td>
                                        <td>
                                            <x-action-buttons
                                            :canEdit="auth()->user()->hasPermission('admin.users.edit')" :canDelete="auth()->user()->hasPermission('admin.users.destroy')" :canShow="auth()->user()->hasPermission('admin.users.show')" :editRoute="route('admin.users.edit',$user)" :deleteRoute="route('admin.users.destroy',$user)" :showRoute="route('admin.users.show',$user)"
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
<script>

</script>
@stop
@endsection
