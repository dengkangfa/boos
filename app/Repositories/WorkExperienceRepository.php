<?php


namespace App\Repositories;


use App\Models\WorkExperience;

class WorkExperienceRepository
{
    use BaseRepository;

    protected $model;

    public function __construct(WorkExperience $workExperience)
    {
        $this->model = $workExperience;
    }
}