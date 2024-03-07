<?php

declare(strict_types=1);

namespace App\Livewire\Components\PromotionBanner\Types;

use Livewire\Component;

class Index extends Component
{
    /**
     * Render the livewire component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('adminhub.livewire.components.promotion-banner.types.index')
            ->layout('adminhub::layouts.base');
    }
}
