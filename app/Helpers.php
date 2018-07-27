<?php

/**
 * Checks to see if the the current page is what is requested.
 *
 * @param  string  $path
 * @param  string  $active
 * @return bool
*/
function set_active($path, $active = 'active')
{
    $path = (array) $path;

    return Request::is(...$path) ? $active : '';
}
