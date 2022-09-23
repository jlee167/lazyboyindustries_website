<?php

namespace App\Repositories\Base;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;


class SingleModelRepository
{
    private Model $model;


    public function __construct(Model $model)
    {
        $this->model = $model;
    }


    public function create(array $attributes) : Model
    {
        return $this->model->create($attributes);
    }


    public function delete(int $id) : bool
    {
        return $this->model->find($id)->delete();
    }


    public function update(int $id, array $attributes) : bool
    {
        return $this->model->find($id)->update($attributes);
    }


    public function all()
    {
        return $this->model->get();
    }


    public function paginate($itemsPerPage = 10)
    {
        return $this->model->paginate($itemsPerPage);
    }


    public function cursorPaginate($column = 'id', $itemsPerPage = 10)
    {
        return $this->model->orderBy($column)->cursorPaginate($itemsPerPage);
    }
}
