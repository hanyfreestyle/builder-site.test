<?php

return [
    'shield_resource' => [
        'should_register_navigation' => true,
        'slug' => 'shield/roles',
        'navigation_sort' => 1500000,
        'navigation_badge' => true,
        'navigation_group' => true,
        'is_globally_searchable' => false,
        'show_model_path' => false,
        'is_scoped_to_tenant' => true,
        'cluster' => null,
    ],
    'tenant_model' => null,

    'auth_provider_model' => [
        'fqcn' => 'App\\Models\\User',
    ],

    'super_admin' => [
        'enabled' => true,
        'name' => 'super_admin',
        'define_via_gate' => false,
        'intercept_gate' => 'before', // after
    ],

//    'panel_admin' => [
//        'enabled' => true,
//        'name' => 'panel_admin',
//    ],

    'panel_user' => [
        'enabled' => true,
        'name' => 'panel_user',
    ],

    'permission_prefixes' => [
        'resource' => [
            'view_any_category',
            'view_any',
            'view',
            'create',
            'update',
            'update_slug',
            'delete',
            'delete_any',
            'restore',
            'restore_any',
            'reorder',
            'force_delete',
            'force_delete_any',
            'publish',
            'replicate',
        ],

        'page' => 'page',
        'widget' => 'widget',
    ],

    'entities' => [
        'pages' => true,
        'widgets' => true,
        'resources' => true,
        'custom_permissions' => false,
    ],

    'generator' => [
        'option' => 'policies_and_permissions',
        'policy_directory' => 'Policies',
        'policy_namespace' => 'Policies',
    ],

    'exclude' => [
        'enabled' => true,

        'pages' => [
            'Dashboard','BackUpFile','ExportDatabase','ListDatabaseTables','ModelsSettings','SiteSettings'
        ],

        'widgets' => [
            'AccountWidget', 'FilamentInfoWidget',
        ],

        'resources' => [
            'FilesListResource','FilesListGroupResource','UserGuidePageResource','UserGuidePhotoResource',
            'DefPhotoResource','MetaTagResource','UploadFilterResource','WebPrivacyResource',
            'AmenityResource','LocationResource','DataProjectStatusResource','DataProjectTypeResource','DataUnitTypeResource','DataUnitViewResource',
            'DataCountryResource',
            'BlogCategoryResource',
//            'ProjectResource','ProjectUnitsResource','ForSaleResource'
        ],
    ],

    'discovery' => [
        'discover_all_resources' => false,
        'discover_all_widgets' => false,
        'discover_all_pages' => false,
    ],

    'register_role_policy' => [
        'enabled' => true,
    ],

];
