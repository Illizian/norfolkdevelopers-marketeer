<?php

namespace App\Policies;

use App\Models\TemplateNotification;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TemplateNotificationPolicy
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
        return $user->can('model.template-notification.read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TemplateNotification  $templateNotification
     * @return mixed
     */
    public function view(User $user, TemplateNotification $templateNotification)
    {
        return $user->can('model.template-notification.read');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('model.template-notification.create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TemplateNotification  $templateNotification
     * @return mixed
     */
    public function update(User $user, TemplateNotification $templateNotification)
    {
        return $user->can('model.template-notification.update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TemplateNotification  $templateNotification
     * @return mixed
     */
    public function delete(User $user, TemplateNotification $templateNotification)
    {
        return $user->can('model.template-notification.delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TemplateNotification  $templateNotification
     * @return mixed
     */
    public function restore(User $user, TemplateNotification $templateNotification)
    {
        return $user->can('model.template-notification.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TemplateNotification  $templateNotification
     * @return mixed
     */
    public function forceDelete(User $user, TemplateNotification $templateNotification)
    {
        return $user->can('model.template-notification.delete');
    }
}
