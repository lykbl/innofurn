<?php

declare(strict_types=1);

namespace App\Domains\Attributes;

enum IndexedAttributesTypes: string
{
    case COLOR       = 'color';
    case MULTISELECT = 'multi-select';
    case CHECKBOX    = 'checkbox';
}
