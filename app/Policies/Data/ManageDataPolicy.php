<?php

namespace App\Policies\Data;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManageDataPolicy {
    use HandlesAuthorization;

    public function viewAny(User $user): bool {
        return $user->can('view_any_data::manage::data');
    }

//    public function viewWebSiteSettings(User $user): bool {
//        return $user->can('web_site_settings_web::setting::web::site::settings');
//    }

    public function view(User $user, Model $model): bool {
        return $user->can('view_data::manage::data');
    }

    public function create(User $user): bool {
        return $user->can('create_data::manage::data');
    }

    public function update(User $user, Model $model): bool {
        return $user->can('update_data::manage::data');
    }

    public function updateSlug(User $user, Model $model): bool {
        return $user->can('update_slug_data::manage::data');
    }

    public function delete(User $user, Model $model): bool {
        return $user->can('delete_data::manage::data');
    }

    public function deleteAny(User $user): bool {
        return $user->can('delete_any_data::manage::data');
    }

    public function forceDelete(User $user, Model $model): bool {
        return $user->can('force_delete_data::manage::data');
    }

    public function forceDeleteAny(User $user): bool {
        return $user->can('force_delete_any_data::manage::data');
    }

    public function restore(User $user, Model $model): bool {
        return $user->can('restore_data::manage::data');
    }

    public function restoreAny(User $user): bool {
        return $user->can('restore_any_data::manage::data');
    }

    public function reorder(User $user): bool {
        return $user->can('reorder_data::manage::data');
    }
}
