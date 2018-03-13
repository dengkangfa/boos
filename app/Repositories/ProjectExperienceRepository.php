<?php

namespace App\Repositories;

use App\Models\ProjectExperience;

class ProjectExperienceRepository
{
    use BaseRepository;

    protected $model;

    public function __construct(projectExperience $projectExperience)
    {
        $this->model = $projectExperience;
    }
}