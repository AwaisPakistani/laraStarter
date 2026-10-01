<?php

namespace App\Repositories\Files;

use App\Models\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Support\Facades\DB;
class RoleRepository implements RoleRepositoryInterface
{
    protected $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        $search = request()->input('search');
        $no_of_items = request()->input('perPage', 10);

        return $this->model
        ->search($search)
        ->paginate($no_of_items);
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Ensure guard_name has a default if not provided
            $data['guard_name'] = $data['guard_name'] ?? 'web';

            return tap($this->model->create($data), function ($role) use ($data) {
                // Convert string IDs like ["1", "2"] into integers like [1, 2]
                $permissions = isset($data['permissions'])
                    ? array_map('intval', $data['permissions'])
                    : [];

                $role->givePermissionTo($permissions);
            });
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $role = $this->model->findOrFail($id);

            // 1. Update the role name
            $role->update([
                'name' => $data['name'] ?? $role->name,
            ]);

            // 2. Extract permissions safely
            $permissionsInput = $data['permissions'] ?? [];

            // 3. Check if inputs are numeric (IDs) or strings (Permission names)
            // If they are numeric strings like "8", "9", convert them to integers.
            // If they are permission names, leave them as strings.
            $permissions = array_map(function ($permission) {
                return is_numeric($permission) ? (int) $permission : $permission;
            }, $permissionsInput);

            // 4. Sync permissions (Spatie handles both arrays of IDs or arrays of names)
            $role->syncPermissions($permissions);

            return $role;
        });
    }

    public function delete($id)
    {
        $model = $this->model->findOrFail($id);
        $model->delete();
        return $model;
    }
    public function toggleStatus($id)
    {
        $role = $this->model->findOrFail($id);
        $role->status = $role->status === 'active' ? 'inactive' : 'active';
        $role->save();
        return $role;
    }
}
