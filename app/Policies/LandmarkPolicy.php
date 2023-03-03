<?php

namespace App\Policies;

use App\Models\Landmark;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LandmarkPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Landmark $landmark): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can_create;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Landmark $landmark): bool
    {
        // Either user has global edit privileges or created the landmark
        return $user->can_edit || ($landmark->user_id === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Landmark $landmark): bool
    {
        // Either user has global privileges or created the landmark
        return $user->can_delete || ($landmark->user_id === $user->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Landmark $landmark): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Landmark $landmark): bool
    {
        // Either user has global privileges or created the landmark
        return $user->can_delete || ($landmark->user_id === $user->id);
    }
}
