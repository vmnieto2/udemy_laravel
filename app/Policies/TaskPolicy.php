<?php

namespace App\Policies;

use App\User;
use App\Task;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;


    public function verify(User $user, Task $task)
    {
        return $user->id === $task->user_id;
    }
}
