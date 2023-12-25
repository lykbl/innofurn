<?php

declare(strict_types=1);

namespace App\Models;

interface Translatable
{
    public function translate($attribute, $locale = null);

    public function translateAttribute($attribute, $locale = null);
}
