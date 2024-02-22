<?php

declare(strict_types=1);

namespace App\Livewire\Inputs;

use Illuminate\View\Component;

class ColorPicker extends Component
{
    public function render()
    {
        return view('adminhub::inputs.color-picker');
    }
}
