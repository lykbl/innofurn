<?php

declare(strict_types=1);

namespace App;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed queryPerformance(callable $callback)
 */
class DebugLogger extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\DebugLoggerService';
    }
}
