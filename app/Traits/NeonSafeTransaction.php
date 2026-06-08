<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

/**
 * Trait NeonSafeTransaction
 *
 * Provides a retry mechanism for database transactions that may fail
 * with SQLSTATE[25P02] due to Neon PostgreSQL's pgBouncer connection
 * pooler reusing connections with stale aborted transaction state.
 *
 * When 25P02 is detected, the connection is forcibly disconnected and
 * the transaction is retried on a brand-new connection from the pool.
 */
trait NeonSafeTransaction
{
    /**
     * Execute a callback inside a DB transaction with automatic retry
     * on SQLSTATE[25P02] (aborted transaction from pgBouncer).
     */
    protected function neonSafeTransaction(callable $callback, int $maxRetries = 2): mixed
    {
        $lastException = null;

        for ($attempt = 0; $attempt <= $maxRetries; $attempt++) {
            try {
                return DB::transaction($callback);
            } catch (QueryException $e) {
                $lastException = $e;

                if ($e->getCode() !== '25P02' || $attempt >= $maxRetries) {
                    throw $e;
                }

                // 25P02: force a completely fresh connection
                DB::disconnect('pgsql');
                DB::reconnect('pgsql');
            }
        }

        throw $lastException;
    }
}
