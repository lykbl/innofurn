<?php

declare(strict_types=1);

namespace App\Livewire\Pages\PromotionBanner;

use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    /**
     * Render the livewire component.
     *
     * @return View
     */
    public function render()
    {
        return view('adminhub.livewire.pages.promotion-banners.index');
    }
}
