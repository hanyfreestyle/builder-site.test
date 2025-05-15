<?php




return [
    \App\Models\RealEstate\BlogPost::class => \App\Policies\RealEstate\BlogPostPolicy::class,
    \App\Models\RealEstate\BlogCategory::class => \App\Policies\RealEstate\BlogPostPolicy::class,
    \App\Models\RealEstate\Developer::class => \App\Policies\RealEstate\DeveloperPolicy::class,

    \App\Models\Data\ManageData::class => \App\Policies\RealEstate\ClientManageDataPolicy::class,
    \App\Models\Data\DataCountry::class => \App\Policies\RealEstate\ClientManageDataPolicy::class,
    \App\Models\RealEstate\Location::class => \App\Policies\RealEstate\ClientManageDataPolicy::class,
    \App\Models\RealEstate\Amenity::class => \App\Policies\RealEstate\ClientManageDataPolicy::class,
    \App\Models\RealEstate\DataProjectType::class => \App\Policies\RealEstate\ClientManageDataPolicy::class,

    \App\Models\RealEstate\Projects::class => \App\Policies\RealEstate\ProjectsPolicy::class,
    \App\Models\RealEstate\ProjectUnits::class => \App\Policies\RealEstate\ProjectUnitsPolicy::class,
    \App\Models\RealEstate\ForSale::class => \App\Policies\RealEstate\ForSalePolicy::class,

];
