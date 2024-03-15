<?php

declare(strict_types=1);

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DebugLogger extends Log
{
    public static function queryPerformance(callable $callback): mixed
    {
        if (!env('QUERY_DEBUGGER_ENABLED', false)) {
            return $callback();
        }

        $logFilters = [
            ['argument', 'not like', '%PhpStorm%'],
            ['argument', '!=', 'SHOW WARNINGS'],
            ['argument', '!=', 'SET net_write_timeout=60'],
            ['argument', 'not like', '%session%'],
            ['command_type', '=', 'Execute'],
        ];
        $lastLog = DB::connection('mysql_debug')
            ->table('general_log')
            ->select('*')
            ->where($logFilters)
            ->orderBy('event_time', 'desc')
            ->limit(1)
            ->first()
        ;

        $result = $callback();

        $logsBetween = DB::connection('mysql_debug')
            ->table('general_log')
            ->where([
                ...$logFilters,
                ['event_time', '>', $lastLog?->event_time],
            ])
            ->orderBy('event_time', 'desc')
            ->get()
        ;

        $queries   = $logsBetween->map(fn ($log) => $log->argument)->join("\n\n");
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);
        static::debug('Executed queries: '.$queries, ['calledFrom' => $backtrace[0]['file'].':'.$backtrace[0]['line']]);

        return $result;
    }
}
