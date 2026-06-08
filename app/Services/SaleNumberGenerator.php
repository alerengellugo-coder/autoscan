<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SaleNumberGenerator
{
    /**
     * Generate a unique sale number.
     *
     * Format: VTA-YYYYMMDD-XXXX
     * Where XXXX is a 4-digit sequential number that resets daily.
     *
     * Example: VTA-20240115-0001
     *
     * @return string
     */
    public static function generate(): string
    {
        $prefix = 'VTA';
        $date = now()->format('Ymd');

        // Use a database lock to prevent race conditions
        $sequence = DB::transaction(function () use ($prefix, $date) {
            // Try to find the latest sale number for today
            $latestNumber = DB::table('sales')
                ->where('sale_number', 'like', "{$prefix}-{$date}-%")
                ->orderByDesc('sale_number')
                ->lockForUpdate()
                ->value('sale_number');

            if ($latestNumber) {
                // Extract the sequence number and increment
                $parts = explode('-', $latestNumber);
                $lastSequence = (int) end($parts);
                $newSequence = $lastSequence + 1;
            } else {
                $newSequence = 1;
            }

            return str_pad($newSequence, 4, '0', STR_PAD_LEFT);
        });

        return "{$prefix}-{$date}-{$sequence}";
    }

    /**
     * Extract the date from a sale number.
     *
     * @param string $saleNumber
     * @return string|null YYYY-MM-DD format or null if invalid format
     */
    public static function extractDate(string $saleNumber): ?string
    {
        $parts = explode('-', $saleNumber);
        if (count($parts) !== 3 || $parts[0] !== 'VTA' || strlen($parts[1]) !== 8) {
            return null;
        }

        $dateStr = $parts[1];
        $year  = substr($dateStr, 0, 4);
        $month = substr($dateStr, 4, 2);
        $day   = substr($dateStr, 6, 2);

        if (!checkdate((int) $month, (int) $day, (int) $year)) {
            return null;
        }

        return "{$year}-{$month}-{$day}";
    }

    /**
     * Check if a given sale number has today's date.
     *
     * @param string $saleNumber
     * @return bool
     */
    public static function isToday(string $saleNumber): bool
    {
        $date = self::extractDate($saleNumber);
        return $date === now()->format('Y-m-d');
    }
}
