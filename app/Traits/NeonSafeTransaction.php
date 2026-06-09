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
 * Key insight: pgBouncer in TRANSACTION mode may assign different
 * server connections between ROLLBACK and BEGIN if they're sent as
 * separate commands. Sending "ROLLBACK; BEGIN;" as a single exec()
 * forces pgBouncer to route both to the same server connection,
 * ensuring a clean transaction start.
 */
trait NeonSafeTransaction
{
    /**
     * Execute a callback inside a DB transaction with automatic retry
     * on SQLSTATE[25P02] (aborted transaction from pgBouncer).
     *
     * Also includes a pre-transaction ROLLBACK to clear any stale
     * aborted transaction state left by pgBouncer.
     */
    protected function neonSafeTransaction(callable $callback, int $maxRetries = 2): mixed
    {
        $lastException = null;

        for ($attempt = 0; $attempt <= $maxRetries; $attempt++) {
            try {
                // Clear any aborted transaction state before starting
                try {
                    DB::connection('pgsql')->getPdo()->exec('ROLLBACK');
                } catch (\Throwable) {
                    // No transaction to rollback, that's fine
                }

                return DB::transaction($callback);
            } catch (\Throwable $e) {
                $lastException = $e;
                $is25P02 = $this->isAbortedTransactionError($e);

                if (!$is25P02 || $attempt >= $maxRetries) {
                    throw $e;
                }

                // Force a brand new connection
                DB::purge('pgsql');
            }
        }

        throw $lastException;
    }

    /**
     * Check if an exception is a PostgreSQL 25P02 aborted transaction error.
     */
    private function isAbortedTransactionError(\Throwable $e): bool
    {
        $code = $e->getCode();
        if ($code === '25P02') {
            return true;
        }
        if (str_contains($e->getMessage(), '25P02')) {
            return true;
        }
        return false;
    }
}
