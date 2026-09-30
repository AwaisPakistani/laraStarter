@extends('layouts.master')

@section('style')
<link rel="stylesheet" href="{{ asset('assets/vendors/iconly/bold.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <h5 class="mb-0 fw-bold">Create Role & Assign Permissions</h5>

            <!-- Global Master Toggle on Top -->
            <div class="d-flex align-items-center gap-2">
                <span class="fw-bold small text-muted">Select All</span>
                <div class="form-check form-switch m-0">
                    <input class="form-check-input" type="checkbox" id="selectAllGlobal" onchange="toggleAllPermissions(this)">
                </div>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="form-label fw-bold">Role Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter role name" required>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th class="text-start">Module</th>

                                <th>Index <br><input type="checkbox" class="form-check-input mt-1 column-toggle" data-action="index" onchange="toggleColumnPermissions('index', this)"></th>
                                <th>Create <br><input type="checkbox" class="form-check-input mt-1 column-toggle" data-action="create" onchange="toggleColumnPermissions('create', this)"></th>
                                <th>Edit <br><input type="checkbox" class="form-check-input mt-1 column-toggle" data-action="edit" onchange="toggleColumnPermissions('edit', this)"></th>
                                <th>Destroy <br><input type="checkbox" class="form-check-input mt-1 column-toggle" data-action="destroy" onchange="toggleColumnPermissions('destroy', this)"></th>
                                <th>Show <br><input type="checkbox" class="form-check-input mt-1 column-toggle" data-action="show" onchange="toggleColumnPermissions('show', this)"></th>
                                <th>Toggle <br>Status <br><input type="checkbox" class="form-check-input mt-1 column-toggle" data-action="toggleStatus" onchange="toggleColumnPermissions('toggleStatus', this)"></th>

                                <th>All Module</th>
                            </tr>
                        </thead>
                       <tbody>
                            @php
                                $actions = ['index', 'create', 'edit', 'destroy', 'show', 'toggleStatus'];
                            @endphp

                            @if(isset($groupedPermissions) && count($groupedPermissions) > 0)
                                @foreach($groupedPermissions as $module => $modulePermissions)
                                    <tr>
                                        <td class="text-start fw-bold text-capitalize">{{ $module }}</td>

                                        @foreach($actions as $action)
                                            @php
                                                $permName = $module . '.' . $action;
                                                $permission = $modulePermissions->firstWhere('name', $permName);
                                            @endphp
                                            <td>
                                                @if($permission)
                                                    <div class="form-check form-switch d-flex justify-content-center m-0">
                                                        <input class="form-check-input permission-checkbox"
                                                               type="checkbox"
                                                               name="permissions[]"
                                                               value="{{ $permission->id }}"
                                                               data-module="{{ $module }}"
                                                               data-action="{{ $action }}">
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
                                                       data-module="{{ $module }}"
                                                       onchange="toggleRowPermissions('{{ $module }}', this)">
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4">Save Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleAllPermissions(masterCheckbox) {
        const isChecked = masterCheckbox.checked;
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = isChecked);
        document.querySelectorAll('.row-toggle').forEach(cb => cb.checked = isChecked);
        document.querySelectorAll('.column-toggle').forEach(cb => cb.checked = isChecked);
    }

    function toggleRowPermissions(moduleName, rowCheckbox) {
        const isChecked = rowCheckbox.checked;
        document.querySelectorAll(`.permission-checkbox[data-module="${moduleName}"]`).forEach(cb => {
            cb.checked = isChecked;
        });
    }

    function toggleColumnPermissions(actionName, columnCheckbox) {
        const isChecked = columnCheckbox.checked;
        document.querySelectorAll(`.permission-checkbox[data-action="${actionName}"]`).forEach(cb => {
            cb.checked = isChecked;
        });
    }
</script>
@endsection
