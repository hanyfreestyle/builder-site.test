<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy {
    use HandlesAuthorization;
    public function viewAny(User $user): bool {
        if ($user->hasRole('super_admin') ){
            return true ;
        }else{
            return $user->can('view_any_role');
        }
    }

    public function view(User $user, Role $role): bool {
        if ($user->hasRole('super_admin') ){
            return true ;
        }else{
            return $user->can('view_any_role');
        }
    }


    public function create(User $user): bool {
        if ($user->hasRole('super_admin') ){
            return true ;
        }else{
            return $user->can('create_role');
        }
    }


    public function update(User $user, Role $role): bool {
        if ($user->hasRole('super_admin') ){
            return true ;
        }else{
            if ($role->id == 1){
                return false;
            }else{
                return $user->can('update_role');
            }
        }
    }

    public function delete(User $user, Role $role): bool {
        return $user->hasRole('super_admin') ;
    }


    public function deleteAny(User $user): bool {
        return $user->can('delete_any_role');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Role $role): bool {
        return $user->can('{{ ForceDelete }}');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool {
        return $user->can('{{ ForceDeleteAny }}');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Role $role): bool {
        return $user->can('{{ Restore }}');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool {
        return $user->can('{{ RestoreAny }}');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Role $role): bool {
        return $user->can('{{ Replicate }}');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool {
        return $user->can('{{ Reorder }}');
    }
}
