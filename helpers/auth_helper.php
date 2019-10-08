<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * This helper function helps to calculate if a date has passed.
 * 
 * @function    expired()
 * @type        helper
 * @param       string  -   valid date
 * @return      bool
 */
if (!function_exists('expired'))
{

    function expired($date)
    {
        // convert the $date in timestamp
        $timestamp = strtotime($date);

        // substract timestamp with current time, divide by total seconds in a
        // day (60*60*24) and round the result.
        $days = floor(($timestamp - time()) / 86400);

        // if the value is less than zero, then the date has expired
        return ($days < 0);
    }

}