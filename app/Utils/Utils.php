<?php

namespace App\Utils;

use DateTime;
use DateTimeZone;
use Ramsey\Uuid\Uuid;

class Utils
{
    public static function generateUUID() {
        return Uuid::uuid4()->toString();
    }

    public static function getCurrentTime() {
        $dateTime = new DateTime();
        $dateTime->setTimezone(new DateTimeZone('Australia/Brisbane'));
        $dateTimeString = $dateTime->format("c");
        return explode('+', $dateTimeString)[0];       
    }

    public static function calculateDuration($timeOneText, $timeTwoText) {
        $timeOneDateTime = new DateTime($timeOneText);
        $timeTwoDateTime = new DateTime($timeTwoText);
        $duration = date_diff($timeTwoDateTime, $timeOneDateTime);

        $days = $duration->d;
        $hours =  $days * 24 + $duration->h;
        $minutes = $duration->i;
        $seconds = $duration->s;

        return sprintf(
            "%s:%s:%s", 
            str_pad((string) $hours, 2, "0", STR_PAD_LEFT),
            str_pad((string) $minutes, 2, "0", STR_PAD_LEFT),
            str_pad((string) $seconds, 2, "0", STR_PAD_LEFT)
        );
    }

    public static function getDateFromDateTime($dateTimeText) {
        $dateTime = new DateTime($dateTimeText);
        return $dateTime->format('d M Y');
    }

    public static function formatDateTimeForDisplay($dateTimeText) {
        $dateTime = new DateTime($dateTimeText);
        return $dateTime->format('d M Y H:i');
    }

    public static function getTimeFromDateTime($dateTimeText) {
        $dateTime = new DateTime($dateTimeText);
        return $dateTime->format('H:i:s');
    }

    public static function trimAllString($element) {
        if (is_string($element) && empty(trim($element))) {
            return NULL;
            
        } else if (is_string($element)) {
            return trim($element);
            
        } else if (is_array($element)) {
            foreach ($element as $key => $value) {
                $element[$key] = self::trimAllString($value);
            }

            return $element;

        } else {
            return $element;
        }
    }

    public static function paginate($query, $perPage, $currentPage) {
        return [
            'result' => $query->paginate($perPage, 'default', $currentPage),
            'pager' => $query->pager,
        ];
    }
}