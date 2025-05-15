<?php

namespace App\Policies\WebSetting;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Access\HandlesAuthorization;

class WebSiteSettingsPolicy {
    use HandlesAuthorization;


//    public function viewAny(User $user): bool {
//        return $user->can('view_any_web::setting::web::site::settings');
//    }

    public function viewWebSiteSettings(User $user): bool {
        return $user->can('web_site_settings_web::setting::web::site::settings');
    }

    public function viewWebModelsSettings(User $user): bool {
        return $user->can('web_models_settings_web::setting::web::site::settings');
    }

    public function viewMetaTag(User $user): bool {
        return $user->can('meta_tag_web::setting::web::site::settings');
    }

    public function viewDefaultPhoto(User $user): bool {
        return $user->can('default_photo_web::setting::web::site::settings');
    }

    public function viewUploadFilter(User $user): bool {
        return $user->can('upload_filter_web::setting::web::site::settings');
    }

    public function viewWebPrivacy(User $user): bool {
        return $user->can('web_privacy_web::setting::web::site::settings');
    }

    public function view(User $user, Model $model): bool {
        return $user->can('view_web::setting::web::site::settings');
    }

    public function create(User $user): bool {
        return $user->can('create_web::setting::web::site::settings');
    }

    public function update(User $user, Model $model): bool {
        return $user->can('update_web::setting::web::site::settings');
    }

    public function updateSlug(User $user, Model $model): bool {
        return $user->can('update_slug_web::setting::web::site::settings');
    }

    public function delete(User $user, Model $model): bool {
        return $user->can('delete_web::setting::web::site::settings');
    }

    public function deleteAny(User $user): bool {
        return $user->can('delete_any_web::setting::web::site::settings');
    }

    public function forceDelete(User $user, Model $model): bool {
        return $user->can('force_delete_web::setting::web::site::settings');
    }

    public function forceDeleteAny(User $user): bool {
        return $user->can('force_delete_any_web::setting::web::site::settings');
    }

    public function restore(User $user, Model $model): bool {
        return $user->can('restore_web::setting::web::site::settings');
    }

    public function restoreAny(User $user): bool {
        return $user->can('restore_any_web::setting::web::site::settings');
    }

    public function reorder(User $user): bool {
        return $user->can('reorder_web::setting::web::site::settings');
    }
}
