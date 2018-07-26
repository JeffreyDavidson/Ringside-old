<?php

function set_active($path, $active = 'active')
{
    $path = (array) $path;

    return Request::is(...$path) ? $active : '';
}
