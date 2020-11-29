<?php

namespace App\Policies;

use App\Models\ScheduledNotification;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScheduledNotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('model.scheduled-notification.read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScheduledNotification  $scheduledNotification
     * @return mixed
     */
    public function view(User $user, ScheduledNotification $scheduledNotification)
    {
        return $user->can('model.scheduled-notification.read');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('model.scheduled-notification.create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScheduledNotification  $scheduledNotification
     * @return mixed
     */
    public function update(User $user, ScheduledNotification $scheduledNotification)
    {
        return $user->can('model.scheduled-notification.update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScheduledNotification  $scheduledNotification
     * @return mixed
     */
    public function delete(User $user, ScheduledNotification $scheduledNotification)
    {
        return $user->can('model.scheduled-notification.delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScheduledNotification  $scheduledNotification
     * @return mixed
     */
    public function restore(User $user, ScheduledNotification $scheduledNotification)
    {
        return $user->can('model.scheduled-notification.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScheduledNotification  $scheduledNotification
     * @return mixed
     */
    public function forceDelete(User $user, ScheduledNotification $scheduledNotification)
    {
        return $user->can('model.scheduled-notification.delete');
    }
}
