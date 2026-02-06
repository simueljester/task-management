<?php

namespace App\Http\Repositories;

use App\Models\Project;

class ProjectRepository extends BaseRepository
{
    public function __construct(Project $model)
    {
        $this->model = $model;
    }

    public function getAllProjectsForUser(int $userId) : \Illuminate\Database\Eloquent\Collection
    {
        return $this->query()->where('user_id', $userId)->get();
    }
}
