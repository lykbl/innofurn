<?php

declare(strict_types=1);

namespace App\Providers;

use App\FieldTypes\ColorFieldType;
use App\Models\PromotionBanner\PromotionBanner;
use App\Models\Room;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Lunar\Facades\AttributeManifest;
use Lunar\Facades\FieldTypeManifest;
use Lunar\Hub\Auth\Manifest;
use Lunar\Hub\Facades\Menu;
use Lunar\Hub\LunarHub;
use Lunar\Hub\Menu\MenuSlot;

class HubServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        if (!config('lunar-hub.system.enable', true)) {
            return;
        }

        $this->loadRoutesFrom(app_path().'/../routes/adminhub/index.php');
        $this->loadViewsFrom(app_path().'/../resources/views/adminhub/livewire', 'adminhub');
        $this->extendMenuBuilder();
        $this->extendPermissions();
        $this->extendAttributes();

        FieldTypeManifest::add(
            ColorFieldType::class
        );
        LunarHub::remoteScript('https://cdn.jsdelivr.net/npm/vanilla-picker@2.12.2/dist/vanilla-picker.min.js');
        //        LunarHub::remoteStyle('https://cdn.jsdelivr.net/npm/rippleui@1.12.1/dist/css/styles.css');

        $this->registerViewComponents();
    }

    private function extendMenuBuilder(): void
    {
        $this->extendSidebarMenu();
    }

    private function extendSidebarMenu(): void
    {
        /** @var MenuSlot $slot */
        $slot = Menu::slot('sidebar');

        $supportGroup = $slot
            ->group('hub.support')
            ->name(__('adminhub::menu.sidebar.support'))
        ;
        $supportGroup
            ->addItem(function ($item): void {
                $item
                    ->name(__('adminhub::menu.sidebar.chats'))
                    ->handle('hub.chats')
                    ->gate('support:manage-chats')
                    ->route('adminhub.chats.index')
                    ->icon('chat');
            })
            ->addItem(function ($item): void {
                $item
                    ->name(__('adminhub::menu.sidebar.issue-tickets'))
                    ->handle('hub.tickets')
                    ->gate('support:manage-tickets')
                    ->route('adminhub.tickets.index')
                    ->icon('question-mark-circle')
                ;
            })
        ;

        $catalogueGroup = $slot->group('hub.catalogue');
        $bannerGroup    = $catalogueGroup
            ->section('hub.banners')
            ->name(__('adminhub::menu.sidebar.promotion-banners'))
            ->handle('hub.banners')
            ->gate('catalogue:manage-banners')
            ->route('hub.promotion-banners.index')
            ->icon('photograph')
        ;

        $bannerGroup->addItem(function ($menuItem): void {
            $menuItem
                ->name(__('adminhub::menu.sidebar.promotion-banner-types'))
                ->handle('hub.promotion-banner-types')
                ->gate('catalogue:manage-banners')
                ->route('hub.promotion-banner-types.index');
        });

        $roomGroup = $catalogueGroup
            ->section('hub.rooms')
            ->name(__('adminhub::menu.sidebar.rooms'))
            ->handle('hub.rooms')
            ->gate('catalogue:manage-rooms')
            ->route('hub.rooms.index')
            // TODO add icon
//            ->icon('room')
        ;
    }

    private function extendPermissions(): void
    {
        $manifest = app(Manifest::class);

        $manifest->addPermission(function ($permission): void {
            $permission
                ->name(__('adminhub::auth.permissions.catalogue.banners.name'))
                ->handle('catalogue:manage-banners')
                ->description(__('adminhub::auth.permissions.catalogue.banners.description'))
            ;
            $permission
                ->name(__('adminhub::auth.permissions.catalogue.rooms.name'))
                ->handle('catalogue:manage-rooms')
                ->description(__('adminhub::auth.permissions.catalogue.rooms.description'));
        });

        $manifest->addPermission(function ($permission): void {
            $permission
                ->name(__('adminhub::auth.permissions.support.chats.name'))
                ->handle('support:manage-chats')
                ->description(__('adminhub::auth.permissions.support.chats.description'))
            ;
        });

        $manifest->addPermission(function ($permission): void {
            $permission
                ->name(__('adminhub::auth.permissions.support.tickets.name'))
                ->handle('support:manage-tickets')
                ->description(__('adminhub::auth.permissions.support.tickets.description'))
            ;
        });
    }

    private function extendAttributes(): void
    {
        AttributeManifest::addType(PromotionBanner::class);
        AttributeManifest::addType(Room::class);
    }

    private function registerViewComponents(): void
    {
        // Blade Components
        Blade::componentNamespace('App\\Livewire', 'adminhub');
    }
}
