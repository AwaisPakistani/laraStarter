<?php

namespace App\Repositories\Files;

use App\Models\{User,Role};
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
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
            return tap($this->model->create($data), function ($user) use ($data) {
                if (!empty($data['roles'])) {
                    // If IDs were passed, find their names first
                    $roleNames = Role::whereIn('id', $data['roles'])->pluck('name')->toArray();
                    $user->syncRoles($roleNames);
                } else {
                    $user->syncRoles([]);
                }
            });
        });
    }

    // public function update($id, array $data)
    // {
    //     return DB::transaction(function () use ($id, $data) {
    //         $user = $this->model->findOrFail($id);
    //         $user->update($data);

    //         if (!empty($data['roles'])) {
    //             // If IDs were passed, find their names first
    //            $roleIds = isset($data['roles']) ? array_map('intval', $data['roles']) : [];
    //            $user->roles()->sync($roleIds);
    //         } else {
    //             $user->syncRoles([]);
    //         }

    //         return $user;
    //     });
    // }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $user = $this->model->findOrFail($id);

            // Prepare the attributes to update
            $updateData = [
                'name' => $data['name'] ?? $user->name,
                'email' => $data['email'] ?? $user->email,
            ];

            // Only update the password if a new one was provided and is not empty
            if (filled($data['password'])) {
                $updateData['password'] = $data['password']; // or Hash::make()
            }

            // Perform the update
            $user->update($updateData);

            // Sync roles safely with integer casting
            $roleIds = isset($data['roles']) ? array_map('intval', $data['roles']) : [];
            $user->roles()->sync($roleIds);
            return $user;
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
        $modelName = $this->model->findOrFail($id);
        $modelName->status = $modelName->status === 'active' ? 'inactive' : 'active';
        $modelName->save();
        return $modelName;
    }
}
