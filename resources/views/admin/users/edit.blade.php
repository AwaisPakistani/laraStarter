@extends('layout.master')
@section('content')
<!--style-->
@section('style')
<link rel="stylesheet" href="{{asset('assets/vendors/iconly/bold.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendors/choices.js/choices.min.css')}}" />
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
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ url()->previous() }}">Users</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Update User</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form action="{{ route('admin.users.update',$user->id) }}" method="POST" class="form">
                            @csrf
                            @method('PUT')
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                             <label for="USER-name-column">Roles</label>
                                            <select class="choices form-select multiple-remove" name="roles[]" multiple="multiple">
                                                <optgroup label="Select Roles">
                                                    @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                        @if(in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())))
                                                            selected
                                                        @endif
                                                        >{{$role->name}}</option>
                                                    @endforeach
                                                    </optgroup>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="USER-name-column">User Name</label>
                                            <input type="text" id="user-name-column" value="{{ old('name',$user->name) }}" class="form-control @error('name')
                                            is-invalid
                                            @enderror"
                                            placeholder="User Name" name="name">
                                            @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="email-id-column">Email</label>
                                            <input type="email" value="{{ old('email',$user->email) }}"id="email-id-column" class="form-control @error('email')
                                            is-invalid
                                            @enderror"
                                            name="email" placeholder="Email">
                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>
                                    </div>
                                     <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="password-id-column">Password</label>
                                            <input type="password" value="{{ old('password') }}" id="password-id-column" class="form-control @error('password')
                                            is-invalid
                                            @enderror"
                                            name="password" placeholder="password">
                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="password-id-column">Confirm Password</label>
                                            <input type="password" value="{{ old('confirm-password') }}"id="confirm-password-id-column" class="form-control @error('confirm-password')
                                            is-invalid
                                            @enderror"
                                            name="confirm-password" placeholder="Confirm-password">
                                            @error('confirm-password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit"
                                        class="btn btn-primary me-1 mb-1">Submit</button>
                                        <a href="{{ url()->previous() }}""
                                        class="btn btn-light-secondary me-1 mb-1">Back</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // Basic multiple Column Form section end -->
</div>
@section('scripts')
<script src="{{asset('assets/vendors/choices.js/choices.min.js')}}"></script>
@stop
@endsection
