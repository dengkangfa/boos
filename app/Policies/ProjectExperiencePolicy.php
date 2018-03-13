<?php

namespace App\Policies;

use App\Models\ProjectExperience;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectExperiencePolicy
{
    use HandlesAuthorization;

    public function destroy(User $user, ProjectExperience $projectExperience)
    {
        return $user->isAuthorOf($projectExperience);
    }
}
