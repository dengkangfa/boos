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