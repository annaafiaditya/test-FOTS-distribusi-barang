<?php

if (! function_exists('format_datetime_wib')) {
    function format_datetime_wib(?string $value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        $utc = new \DateTimeZone('UTC');
        $date = \DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $value, $utc);
        $errors = \DateTimeImmutable::getLastErrors();

        if (
            $date === false
            || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))
            || $date->format('Y-m-d H:i:s') !== $value
        ) {
            throw new \InvalidArgumentException('Format timestamp database tidak valid.');
        }

        return $date->setTimezone(new \DateTimeZone('Asia/Jakarta'))->format('d/m/Y H:i:s') . ' WIB';
    }
}

if (! function_exists('local_date_boundary_to_utc')) {
    function local_date_boundary_to_utc(string $date, bool $endOfDay = false): string
    {
        $local = \DateTimeImmutable::createFromFormat(
            '!Y-m-d',
            $date,
            new \DateTimeZone('Asia/Jakarta'),
        );
        $errors = \DateTimeImmutable::getLastErrors();

        if (
            $local === false
            || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))
            || $local->format('Y-m-d') !== $date
        ) {
            throw new \InvalidArgumentException('Format tanggal lokal tidak valid.');
        }

        if ($endOfDay) {
            $local = $local->setTime(23, 59, 59);
        }

        return $local->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s');
    }
}
