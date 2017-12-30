<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    use BaseRepository;

    /**
     * User Models
     *
     * @var User
     */
    protected $model;

    /**
     * Constructor
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function update($id, $input)
    {
        $user = $this->getById($id);

        return $this->save($user, $input);
    }
}