<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire;
use Lunar\Hub\Facades\Menu;
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

        $this->loadRoutesFrom(app_path() . '/../routes/adminhub/index.php');
        $this->extendMenuBuilder();

//        $this->loadViewsFrom(__DIR__ . '/../resources/views/adminhub/', 'adminhub');
//        $this->registerViewComponents();
    }

    private function extendMenuBuilder(): void
    {
        $this->extendSidebarMenu();
    }

    private function extendSidebarMenu(): void
    {
        $slot = Menu::slot('sidebar');

        $supportGroup = $slot
            ->group('hub.support')
            ->name(__('adminhub::menu.sidebar.support'));
        $supportGroup
            ->addItem(function ($item) {
                $item
                    ->name(__('adminhub::menu.sidebar.chats'))
                    ->handle('hub.chats')
                    ->route('adminhub.chats.index')
                    ->icon('chat');
            });
        $supportGroup
            ->addItem(function ($item) {
                $item
                    ->name(__('adminhub::menu.sidebar.issue-tickets'))
                    ->handle('hub.tickets')
                    ->route('adminhub.tickets.index')
                    ->icon('question-mark-circle');
            });
    }

    private function registerViewComponents()
    {
        // Blade Components
//        Blade::componentNamespace('App\\Views\\Components', 'hub');
    }
}