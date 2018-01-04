<?php

namespace App\Repositories;

trait BaseRepository
{
    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function store($input)
    {
        return $this->save($this->model, $input);
    }

    /**
     * Update columns in the record by id.
     *
     * @param $id
     * @param $input
     * @return mixed
     */
    public function updateColumn($id, $input)
    {
        $this->model = $this->getById($id);

        foreach ($input as $key => $value) {
            $this->model->{$key} = $value;
        }

        return $this->model->save();
    }

    public function update($id, $input)
    {
        $this->model = $this->getById($id);

        return $this->save($this->model, $input);
    }

    /**
     * Save the input's data.
     *
     * @param  $model
     * @param  $input
     * @return User
     */
    public function save($model, $input)
    {
        $model->fill($input);

        $model->save();

        return $model;
    }
}