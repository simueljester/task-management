<?php

namespace App\Http\Repositories;

use App\Models\Task;

class TaskRepository extends BaseRepository
{
    public function __construct(Task $model)
    {
        $this->model = $model;
    }

    public function getAllTasksForUser(int $userId) : \Illuminate\Database\Eloquent\Collection
    {
        return $this->query()->where('user_id', $userId)->orderBy('priority')->get();
    }
}
