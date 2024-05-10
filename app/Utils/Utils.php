<?php

namespace App\Utils;

use DateTime;
use DateTimeZone;
use Ramsey\Uuid\Uuid;

class Utils
{
    /**
     * Generates a random UUID.
     *
     * @return string The generated UUID.
     */
    public static function generateUUID()
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * Get the current time in the 'Australia/Brisbane' timezone
     * without the timezone offset.
     *
     * @return string The current time in ISO format.
     */
    public static function getCurrentTime()
    {
        // Create a new DateTime object
        $dateTime = new DateTime();

        // Set the timezone to 'Australia/Brisbane'
        $dateTime->setTimezone(new DateTimeZone('Australia/Brisbane'));

        // Format the datetime string to ISO 8601 format
        $dateTimeString = $dateTime->format("c");

        // Split the datetime string to remove the timezone offset
        return explode('+', $dateTimeString)[0];
    }

    /**
     * Calculate the duration between two given times.
     *
     * @param string $timeOneText The first time as a string.
     * @param string $timeTwoText The second time as a string.
     * @return string The duration formatted as "hours:minutes:seconds".
     */
    public static function calculateDuration($timeOneText, $timeTwoText)
    {
        // Create DateTime objects for the given times
        $timeOneDateTime = new DateTime($timeOneText);
        $timeTwoDateTime = new DateTime($timeTwoText);
        
        // Calculate the duration between the two times
        $duration = date_diff($timeTwoDateTime, $timeOneDateTime);

        // Extract days, hours, minutes, and seconds from the duration
        $days = $duration->d;
        $hours =  $days * 24 + $duration->h;
        $minutes = $duration->i;
        $seconds = $duration->s;

        // Format the duration as "hours:minutes:seconds"
        return sprintf(
            "%s:%s:%s",
            str_pad((string) $hours, 2, "0", STR_PAD_LEFT),
            str_pad((string) $minutes, 2, "0", STR_PAD_LEFT),
            str_pad((string) $seconds, 2, "0", STR_PAD_LEFT)
        );
    }

    /**
     * Extracts the date from a datetime string.
     *
     * @param string $dateTimeText The datetime string.
     * @return string The extracted date formatted as 'd M Y'.
     */
    public static function getDateFromDateTime($dateTimeText)
    {
        $dateTime = new DateTime($dateTimeText);
        return $dateTime->format('d M Y');
    }

    /**
     * Formats a datetime string for display.
     *
     * @param string $dateTimeText The datetime string to format.
     * @return string The formatted datetime string (including date and time).
     */
    public static function formatDateTimeForDisplay($dateTimeText)
    {
        // Create a DateTime object from the given datetime string
        $dateTime = new DateTime($dateTimeText);
        
        // Format and return the datetime string with date and time
        return $dateTime->format('d M Y H:i');
    }

    /**
     * Extracts the time from a datetime string.
     *
     * @param string $dateTimeText The datetime string.
     * @return string The extracted time formatted as 'H:i:s'.
     */
    public static function getTimeFromDateTime($dateTimeText)
    {
        $dateTime = new DateTime($dateTimeText);
        return $dateTime->format('H:i:s');
    }

    /**
     * Recursively trims whitespace from the beginning and end of strings within an array.
     * If the trimmed string is empty, returns NULL.
     *
     * @param mixed $element The element to trim (can be a string or an array).
     * @return mixed The trimmed element or NULL.
     */
    public static function trimAllString($element)
    {
        if (is_string($element) && empty(trim($element))) {
            // If it's a string and trimmed string is empty, return NULL
            return NULL;

        } elseif (is_string($element)) {
            // If it's a string and not empty, trim and return it
            return trim($element);

        } elseif (is_array($element)) {
            // If it's an array, recursively trim each element
            foreach ($element as $key => $value) {
                $element[$key] = self::trimAllString($value);
            }
            return $element;

        } else {
            // If it's neither a string nor an array, return the element
            return $element;
        }
    }


    /**
     * Paginates the query results.
     *
     * @param \CodeIgniter\Database\Query|mixed $query The query to paginate.
     * @param int $perPage The number of items per page.
     * @param int $currentPage The current page number.
     * @return array An array containing the paginated results and the pager instance.
     */
    public static function paginate($query, $perPage, $currentPage)
    {
        return [
            'result' => $query->paginate($perPage, 'default', $currentPage), // Paginate the query
            'pager' => $query->pager, // Get the pager instance
        ];
    }

}