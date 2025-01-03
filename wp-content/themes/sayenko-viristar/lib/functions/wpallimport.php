<?php

// WP All Import - Custom PHP Functions

function viristar_filename($url)
{
    // $path = parse_url($url, PHP_URL_PATH);
    return basename($url);
}
