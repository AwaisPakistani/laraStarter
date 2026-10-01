@extends('layouts.master')
@section('style')
<link rel="stylesheet" href="{{ asset('assets/vendors/iconly/bold.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <h5 class="mb-0 fw-bold">Update Role & Sync Permissions</h5>

            <!-- Global Master Toggle on Top -->
            <div class="d-flex align-items-center gap-2">
                <span class="fw-bold small text-muted">Select All</span>
                <div class="form-check form-switch m-0">
                    <input class="form-check-input" type="checkbox" id="selectAllGlobal" onchange="toggleAllPermissions(this)">
                </div>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('roles.update', $Role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="form-label fw-bold">Role Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $Role->name) }}" placeholder="Enter role name" required>
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
                                // Ensure these match your exact action suffixes in the database
                                $actions = ['index', 'create', 'edit', 'destroy', 'show', 'toggleStatus'];
                            @endphp

                            @if(isset($groupedPermissions) && count($groupedPermissions) > 0)
                                @foreach($groupedPermissions as $module => $modulePermissions)
                                    <tr>
                                        <td class="text-start fw-bold text-capitalize">{{ $module }}</td>

                                        @foreach($actions as $action)
                                            @php
                                                // This constructs e.g. 'users.toggleStatus'
                                                $permName = $module . '.' . $action;

                                                // Fallback check to find it even if case differs slightly in DB
                                                $permission = $modulePermissions->first(function ($p) use ($permName) {
                                                    return strtolower($p->name) === strtolower($permName);
                                                });
                                            @endphp
                                            <td>
                                                @if($permission)
                                                    <div class="form-check form-switch d-flex justify-content-center m-0">
                                                        <input class="form-check-input permission-checkbox"
                                                               type="checkbox"
                                                               name="permissions[]"
                                                               value="{{ $permission->id }}"
                                                               data-module="{{ $module }}"
                                                               data-action="{{ $action }}"
                                                               {{{ $Role->permissions->contains($permission->id) ? 'checked' : '' }}}>
                                                    </div>
                                                @else
                                                    <span class="text-muted" title="Missing: {{ $permName }}">-</span>
                                                @endif
                                            </td>
                                        @endforeach

                                        <!-- Row-level Toggle -->
                                        <td>
                                            @php
                                                // Check if every permission belonging to this module is contained in the role's permissions
                                                $allRowChecked = $modulePermissions->isNotEmpty() && $modulePermissions->every(function($p) use ($Role) {
                                                    return $Role->permissions->contains($p->id);
                                                });
                                            @endphp
                                            <div class="form-check form-switch d-flex justify-content-center m-0">
                                                <input class="form-check-input row-toggle"
                                                    type="checkbox"
                                                    data-module="{{ $module }}"
                                                    onchange="toggleRowPermissions('{{ $module }}', this)"
                                                    {{ $allRowChecked ? 'checked' : '' }}>
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
                     <a href="{{ url()->previous() }}""
                                        class="btn btn-light-secondary me-1 mb-1">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Check if all permission checkboxes are checked, then turn on global 'Select All'
        const allPermissions = document.querySelectorAll('.permission-checkbox');
        if (allPermissions.length > 0) {
            const allChecked = Array.from(allPermissions).every(cb => cb.checked);
            if (allChecked) {
                document.getElementById('selectAllGlobal').checked = true;
            }
        }
    });

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
