@extends('layouts.master')
@section('content')
<!--style-->
@section('style')
<link rel="stylesheet" href="{{asset('assets/vendors/iconly/bold.css')}}">
@stop
<!--/style-->
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Roles</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ url()->previous() }}">Roles</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <form action="{{ route('roles.store') }}" method="POST" class="form">@csrf
                    <div class="col-md-12 col-12">
                         <div class="form-group">
                                            <label for="name-column">User Name</label>
                                            <input type="text" id="user-name-column" value="{{ old('name') }}" class="form-control @error('name')
                                            is-invalid
                                            @enderror"
                                            placeholder="Name" name="name">
                                            @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                    </div>

               <div class="card" x-data="permissionToggleManager()">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Assign Permissions</h5>

                        <!-- Global Master Toggle on Top -->
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold small text-muted">Select All</span>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" id="selectAllGlobal" @change="toggleAll($event)">
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                                <div class="row">

                                    <div class="col-md-12 md-12">
                                        <div class="table-responsive">
                                        <table class="table table-bordered align-middle text-center">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-start">Module</th>
                                                    <th>Index</th>
                                                    <th>Create</th>
                                                    <th>Edit</th>
                                                    <th>Destroy</th>
                                                    <th>Show</th>
                                                    <th>Toggle Status</th>
                                                    <th>All Module</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $actions = ['index', 'create', 'edit', 'destroy', 'show', 'toggleStatus'];
                                                @endphp

                                                @foreach($groupedPermissions as $module => $modulePermissions)
                                                    <tr>
                                                        <!-- Module Name Displayed Vertically -->
                                                        <td class="text-start fw-bold text-capitalize">{{ $module }}</td>

                                                        <!-- Loop through actions horizontally -->
                                                        @foreach($actions as $action)
                                                            @php
                                                                $permName = "{$module}.{$action}";
                                                                $permission = $modulePermissions->firstWhere('name', $permName);
                                                            @endphp
                                                            <td>
                                                                @if($permission)
                                                                    <div class="form-check form-switch d-flex justify-content-center m-0">
                                                                        <input class="form-check-input permission-checkbox"
                                                                            type="change"
                                                                            type="checkbox"
                                                                            name="permissions[]"
                                                                            value="{{ $permission->id }}"
                                                                            data-module="{{ $module }}">
                                                                    </div>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                        @endforeach

                                                        <!-- Row-level Toggle -->
                                                        <td>
                                                            <div class="form-check form-switch d-flex justify-content-center m-0">
                                                                <input class="form-check-input row-toggle"
                                                                    type="checkbox"
                                                                    @change="toggleRow('{{ $module }}', $event)">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                         <div class="col-12 d-flex justify-content-end">
                                        <button type="submit"
                                        class="btn btn-primary me-1 mb-1">Submit</button>
                                        <a href="{{ url()->previous() }}""
                                        class="btn btn-light-secondary me-1 mb-1">Back</a>
                                    </div>
                                    </div>
                                </div>

                            </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // Basic multiple Column Form section end -->
</div>
@section('scripts')
  <!-- Alpine.js to handle the switch logic smoothly -->
<script src="//unpkg.com/alpinejs" defer></script>
<script>
    function permissionToggleManager() {
        return {
            toggleAll(event) {
                const isChecked = event.target.checked;
                // Toggle all permission switches
                document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = isChecked);
                // Toggle all row-level switches to match
                document.querySelectorAll('.row-toggle').forEach(cb => cb.checked = isChecked);
            },
            toggleRow(module, event) {
                const isChecked = event.target.checked;
                // Toggle all checkboxes belonging to this specific module row
                document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`).forEach(cb => cb.checked = isChecked);
            }
        }
    }
</script>
@stop
@endsection
