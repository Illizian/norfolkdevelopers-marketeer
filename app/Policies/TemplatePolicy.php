<?php

namespace App\Policies;

use App\Models\Template;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TemplatePolicy
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
        return $user->can('model.template.read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Template  $template
     * @return mixed
     */
    public function view(User $user, Template $template)
    {
        return $user->can('model.template.read');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('model.template.create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Template  $template
     * @return mixed
     */
    public function update(User $user, Template $template)
    {
        return $user->can('model.template.update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Template  $template
     * @return mixed
     */
    public function delete(User $user, Template $template)
    {
        return $user->can('model.template.delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Template  $template
     * @return mixed
     */
    public function restore(User $user, Template $template)
    {
        return $user->can('model.template.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Template  $template
     * @return mixed
     */
    public function forceDelete(User $user, Template $template)
    {
        return $user->can('model.template.delete');
    }
}
