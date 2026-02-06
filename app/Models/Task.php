<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'priority',
        'user_id',
        'title',
        'description',
        'status',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function (Task $task) {
            if (! is_null($task->priority)) {
                return;
            }
            $max = static::where('user_id', $task->user_id)->max('priority');
            $task->priority = ($max ?? 0) + 1;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
