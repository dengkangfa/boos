<?php

namespace App\Repositories;

use App\Models\EducationInfo;

class EducationInfoRepository
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
     * @param EducationInfo $educationInfo
     */
    public function __construct(EducationInfo $educationInfo)
    {
        $this->model = $educationInfo;
    }
}