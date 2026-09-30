<?php

namespace App\Repositories\Files;

use App\Models\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;

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
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $model = $this->model->findOrFail($id);
        $model->update($data);
        return $model;
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
