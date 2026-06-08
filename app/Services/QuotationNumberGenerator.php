<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class QuotationNumberGenerator
{
    /**
     * Generate a unique quotation number.
     *
     * Format: COT-YYYYMMDD-XXXX
     * Where XXXX is a 4-digit sequential number that resets daily.
     *
     * Example: COT-20240115-0001
     *
     * @return string
     */
    public static function generate(): string
    {
        $prefix = 'COT';
        $date = now()->format('Ymd');

        // Use a database lock to prevent race conditions
        $sequence = DB::transaction(function () use ($prefix, $date) {
            // Try to find the latest quotation number for today
            $latestNumber = DB::table('quotations')
                ->where('quotation_number', 'like', "{$prefix}-{$date}-%")
                ->orderByDesc('quotation_number')
                ->lockForUpdate()
                ->value('quotation_number');

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
     * Extract the date from a quotation number.
     *
     * @param string $quotationNumber
     * @return string|null YYYY-MM-DD format or null if invalid format
     */
    public static function extractDate(string $quotationNumber): ?string
    {
        $parts = explode('-', $quotationNumber);
        if (count($parts) !== 3 || $parts[0] !== 'COT' || strlen($parts[1]) !== 8) {
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
     * Check if a given quotation number has today's date.
     *
     * @param string $quotationNumber
     * @return bool
     */
    public static function isToday(string $quotationNumber): bool
    {
        $date = self::extractDate($quotationNumber);
        return $date === now()->format('Y-m-d');
    }
}
