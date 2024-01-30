<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lunar\Hub\Facades\Menu;

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
        $this->extendMenuBuilder();

        // $this->registerViewComponents();
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
            ->addItem(function ($item): void {
                $item
                    ->name(__('adminhub::menu.sidebar.chats'))
                    ->handle('hub.chats')
                    ->route('adminhub.chats.index')
                    ->icon('chat');
            });
        $supportGroup
            ->addItem(function ($item): void {
                $item
                    ->name(__('adminhub::menu.sidebar.issue-tickets'))
                    ->handle('hub.tickets')
                    ->route('adminhub.tickets.index')
                    ->icon('question-mark-circle');
            });
    }

    private function registerViewComponents(): void
    {
        // Blade Components
        //        Blade::componentNamespace('App\\Views\\Components', 'hub');
    }
}
