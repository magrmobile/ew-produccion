<?php

namespace App\Services;

use DateTimeImmutable;
use DateTimeZone;

class InfileDateTime
{
    public static function databaseValue($value)
    {
        if (!is_string($value) || trim($value) === '') {
            return null;
        }
        $value = trim($value);
        $format = null;
        if (preg_match('/^\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}:\d{2}$/D', $value)) {
            $format = '!d/m/Y H:i:s';
        } elseif (preg_match('/^(\d{4}-\d{2}-\d{2})(T| )(\d{2}:\d{2}:\d{2})(\.\d{1,9})?(Z|[+-]\d{2}:?\d{2})?$/D', $value, $parts)) {
            $fraction = isset($parts[4]) ? substr($parts[4], 0, 7) : '';
            $zone = isset($parts[5]) ? $parts[5] : '';
            if ($zone === 'Z') {
                $zone = '+00:00';
            }
            $value = $parts[1].$parts[2].$parts[3].$fraction.$zone;
            $format = '!Y-m-d'.($parts[2] === 'T' ? '\\T' : ' ').'H:i:s';
            $format .= $fraction !== '' ? '.u' : '';
            $format .= $zone !== '' ? (strpos($zone, ':') !== false ? 'P' : 'O') : '';
        }
        if ($format === null) {
            return null;
        }
        try {
            $timezone = new DateTimeZone(config('app.timezone', 'America/El_Salvador'));
            $date = DateTimeImmutable::createFromFormat($format, $value, $timezone);
            $errors = DateTimeImmutable::getLastErrors();
            if (!$date || ($errors && ($errors['warning_count'] || $errors['error_count']))) {
                return null;
            }
            return $date->setTimezone($timezone)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }
}
