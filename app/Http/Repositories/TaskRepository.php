<?php

namespace App\Http\Repositories;

use App\Models\Task;

class TaskRepository extends BaseRepository
{
    public function __construct(Task $model)
    {
        $this->model = $model;
    }

    public function getAllTasksForUser(int $userId, ?int $projectId = null) : \Illuminate\Database\Eloquent\Collection
    {
        $query = $this->query()->where('user_id', $userId);

        if ($projectId !== null) {
            $query->where('project_id', $projectId);
        }

        return $query->orderBy('priority')->get();
    }
}
