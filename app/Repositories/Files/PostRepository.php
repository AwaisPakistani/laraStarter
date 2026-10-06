<?php

namespace App\Repositories\Files;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;

class PostRepository implements PostRepositoryInterface
{
    protected $model;

    public function __construct(Post $model)
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
            return $this->model->create($data);
        });
    }

    public function update($id, array $data)
    {
         return DB::transaction(function () use ($id,$data) {
            $model = $this->model->findOrFail($id);
            $model->update($data);
            return $model;
        });
    }
    public function delete($id)
    {
         return DB::transaction(function () use ($id,$data) {
            $model = $this->model->findOrFail($id);
            $model->delete();
            return $model;
        });
    }
    public function toggleStatus($id)
    {
        return DB::transaction(function () use ($id,$data) {
            $modelName = $this->model->findOrFail($id);
            $modelName->status = $modelName->status === 'active' ? 'inactive' : 'active';
            $modelName->save();
            return $modelName;
        });
    }
}
