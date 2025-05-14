<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Pages\MyProfileCustomPage;
use App\Filament\Plugins\AccordionSidebarPlugin;
use App\Helpers\FilamentAstrotomic\FilamentAstrotomicTranslatablePlugin;
use App\Traits\Providers\PolicyProviderTraits;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Enums\ThemeMode;
use Filament\FontProviders\GoogleFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\UserMenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;

class AdminPanelProvider extends PanelProvider {
    use PolicyProviderTraits;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function boot(): void {
        self::loadPolicyList();
        self::loadClientPolicyList();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function panel(Panel $panel): Panel {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->globalSearch(false)
            ->login()
            ->colors([
                'primary' => Color::Sky,
            ])
            ->userMenuItems(array_filter([
                file_exists(base_path('routes/Admin/user-guide/user-guide.php'))
                    ? MenuItem::make()
                    ->label(__('user-guide/user-guide-page.user_menu'))
                    ->url(fn () => route('helper.user-guide.show', 'index'), shouldOpenInNewTab: true)
                    ->icon('heroicon-o-book-open')
                    : null,
            ]))
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->plugins([
                new AccordionSidebarPlugin(),
                FilamentBackgroundsPlugin::make()->imageProvider(
                    MyImages::make()->directory(getBackgroundsDirectory()) # img or triangles
                ),
                FilamentAstrotomicTranslatablePlugin::make(),
                FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 1, 'sm' => 2, 'lg' => 3
                    ])->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1, 'sm' => 2, 'lg' => 4,
                    ])->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
                BreezyCore::make()->myProfile(
                    shouldRegisterUserMenu: true, // Sets the 'account' link in the panel User Menu (default = true)
                    shouldRegisterNavigation: true, // Adds a main navigation item for the My Profile page (default = false)
                    navigationGroup: 'Settings', // Sets the navigation group for the My Profile page (default = null)
                    hasAvatars: true, // Enables the avatar upload form component (default = false)
                    slug: 'my-profile' // Sets the slug for the profile page (default = 'my-profile')
                )->customMyProfilePage(MyProfileCustomPage::class)
                    ->enableTwoFactorAuthentication()
                    ->avatarUploadComponent(fn($fileUpload) => $fileUpload->disableLabel())
                // OR, replace with your own component
//                    ->avatarUploadComponent(
//                        fn() => WebpImageUpload::make('avatar_url')
//                            ->uploadDirectory('admin-profile') // تحديد مجلد رفع الصور
//                            ->resize(300, 300, 90) // تحديد الأبعاد والجودة
//                            ->nullable()
//                    )
                ,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->font($this->getFontBasedOnLocale(), provider: GoogleFontProvider::class)
            ->topNavigation(false)
            ->sidebarWidth('20rem')
            ->sidebarFullyCollapsibleOnDesktop()
            ->sidebarCollapsibleOnDesktop()
            ->collapsedSidebarWidth('9rem')
            ->maxContentWidth(MaxWidth::Full)
            ->darkMode(true)
            ->defaultThemeMode(ThemeMode::Dark)
            ->brandLogo(getbrandLogo())
            ->favicon(getFavIcon())
            ->brandLogoHeight('2.6rem')
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    protected function getFontBasedOnLocale(): string {
        return "Tajawal";
//        return app()->getLocale() === 'ar' ? 'Tajawal' : 'Roboto';
    }


}
