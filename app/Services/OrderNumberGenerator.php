<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class OrderNumberGenerator
{
    /**
     * Generate a unique service order number.
     *
     * Format: OS-YYYYMMDD-XXXX
     * Where XXXX is a 4-digit sequential number that resets daily.
     *
     * Example: OS-20240115-0001
     *
     * @return string
     */
    public static function generate(): string
    {
        $prefix = 'OS';
        $date = now()->format('Ymd');

        // Use a database lock to prevent race conditions
        $sequence = DB::transaction(function () use ($prefix, $date) {
            // Try to find the latest order number for today
            $latestNumber = DB::table('service_orders')
                ->where('order_number', 'like', "{$prefix}-{$date}-%")
                ->orderByDesc('order_number')
                ->lockForUpdate()
                ->value('order_number');

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
     * Extract the date from an order number.
     *
     * @param string $orderNumber
     * @return string|null YYYY-MM-DD format or null if invalid format
     */
    public static function extractDate(string $orderNumber): ?string
    {
        $parts = explode('-', $orderNumber);
        if (count($parts) !== 3 || $parts[0] !== 'OS' || strlen($parts[1]) !== 8) {
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
     * Check if a given order number has today's date.
     *
     * @param string $orderNumber
     * @return bool
     */
    public static function isToday(string $orderNumber): bool
    {
        $date = self::extractDate($orderNumber);
        return $date === now()->format('Y-m-d');
    }
}
