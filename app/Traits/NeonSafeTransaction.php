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
     */
    protected function neonSafeTransaction(callable $callback, int $maxRetries = 3): mixed
    {
        $lastException = null;

        for ($attempt = 0; $attempt <= $maxRetries; $attempt++) {
            $pdo = null;
            try {
                $pdo = DB::connection('pgsql')->getPdo();
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

                // CRITICAL: Send ROLLBACK then BEGIN as a SINGLE statement.
                // This ensures pgBouncer keeps us pinned to the same server
                // connection for both operations, clearing any aborted state
                // and immediately starting a clean transaction.
                $pdo->exec('ROLLBACK; BEGIN;');

                // Run callback — Eloquent uses the same underlying PDO
                // which is now in a clean transaction on a known-good connection.
                $result = $callback();

                $pdo->exec('COMMIT');
                return $result;
            } catch (\Throwable $e) {
                $lastException = $e;

                // Try to clean up the transaction state
                try {
                    @$pdo->exec('ROLLBACK');
                } catch (\Throwable $re) {
                    // Connection might be completely broken
                }

                // Check if this is a 25P02 error worth retrying
                $is25P02 = $this->isAbortedTransactionError($e);

                if (!$is25P02 || $attempt >= $maxRetries) {
                    throw $e;
                }

                // Force Laravel to create a brand new PDO connection
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
        if ($code === '25P02' || $code === 25P02) {
            return true;
        }
        if (str_contains($e->getMessage(), '25P02')) {
            return true;
        }
        return false;
    }
}
