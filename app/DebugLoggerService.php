<?php

declare(strict_types=1);

namespace App;

use Blink;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DebugLoggerService
{
    public function queryPerformance(callable $callback): mixed
    {
        if (false === $this->canRunPerformanceQuery()) {
            return $callback();
        }

        $logFilters = [
            ['argument', 'not like', '%PhpStorm%'],
            ['argument', '!=', 'SHOW WARNINGS'],
            ['argument', '!=', 'SET net_write_timeout=60'],
            ['argument', 'not like', '%session%'],
            ['command_type', '=', 'Execute'],
        ];
        $lastLog = DB::connection('mysql_mysql')
            ->table('general_log')
            ->select('*')
            ->where($logFilters)
            ->orderBy('event_time', 'desc')
            ->limit(1)
            ->first()
        ;

        $result = $callback();

        $logsBetween = DB::connection('mysql_mysql')
            ->table('general_log')
            ->where([
                ...$logFilters,
                ['event_time', '>', $lastLog?->event_time],
            ])
            ->orderBy('event_time', 'asc')
            ->get()
        ;

        $queries   = $logsBetween->map(fn ($log) => $log->argument)->join("\n\n");
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);
        Log::debug('Executed queries: '.$queries, ['calledFrom' => $backtrace[0]['file'].':'.$backtrace[0]['line']]);

        return $result;
    }

    private function canRunPerformanceQuery(): bool
    {
        return Blink::once('debug_logger_query_performance_check', function () {
            if (!env('QUERY_DEBUGGER_ENABLED', false)) {
                return false;
            }

            $settings = DB::connection('mysql_performance_schema')
                ->table('global_variables')
                ->select('variable_value', 'variable_name')
                ->whereIn('variable_name', ['log_output', 'general_log'])
                ->pluck('variable_value', 'variable_name');
            $valid = true;
            foreach ($settings as $key => $value) {
                if ('TABLE' !== $settings['log_output']) {
                    Log::debug('log_output must be set to TABLE');
                    $valid = false;
                }
                if ('ON' !== $settings['general_log']) {
                    Log::debug('general_log must be set to ON');
                    $valid = false;
                }
            }

            return $valid;
        });
    }
}
