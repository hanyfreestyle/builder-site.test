<?php

namespace App\Traits\Providers;

use Illuminate\Support\Facades\Gate;

trait PolicyProviderTraits {

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function loadPolicyList(): void {
        self::loadSettingsPolicy();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function loadClientPolicyList($clientFolder = true): void {
        $listPolicy = client_config('policy-map', $clientFolder);
        foreach ($listPolicy as $model => $policy) {
            if (class_exists($model) && class_exists($policy)) {
                Gate::policy($model, $policy);
            }
        }
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function loadPolicyExistFile($policyArray = []): void {
        foreach ($policyArray as $model => $policy) {
            if (class_exists($model) && class_exists($policy)) {
                Gate::policy($model, $policy);
            }
        }
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function loadSettingsPolicy(): void {
        $webSettingsPolicy = [
            \App\Models\WebSetting\WebSiteSettings::class => \App\Policies\WebSetting\WebSiteSettingsPolicy::class,
            \App\Models\WebSetting\WebPrivacy::class => \App\Policies\WebSetting\WebSiteSettingsPolicy::class,
            \App\Models\WebSetting\MetaTag::class => \App\Policies\WebSetting\WebSiteSettingsPolicy::class,
            \App\Models\WebSetting\UploadFilter::class => \App\Policies\WebSetting\WebSiteSettingsPolicy::class,
            \App\Models\WebSetting\DefPhoto::class => \App\Policies\WebSetting\WebSiteSettingsPolicy::class,
            \App\Filament\Admin\Pages\WebSetting\SiteSettings::class => \App\Policies\WebSetting\WebSiteSettingsPolicy::class,
            \App\Filament\Admin\Pages\WebSetting\ModelsSettings::class => \App\Policies\WebSetting\WebSiteSettingsPolicy::class,
        ];
        self::loadPolicyExistFile($webSettingsPolicy);
    }

}
