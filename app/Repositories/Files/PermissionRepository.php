<?php

namespace App\Repositories\Files;

use App\Models\Permission;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use Illuminate\Support\Facades\DB;
class PermissionRepository implements PermissionRepositoryInterface
{
    protected $model;

    public function __construct(Permission $model)
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

            return $this->model->create($data);
        });
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
        $modelName = $this->model->findOrFail($id);
        $modelName->status = $modelName->status === 'active' ? 'inactive' : 'active';
        $modelName->save();
        return $modelName;
    }
}
