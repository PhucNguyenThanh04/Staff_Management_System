<?php 

/**
 * Navigate to public path to get files inside
 *
 * @param [type] $path
 * @return void
 */
function asset($path)
{
    return BASE_PATH.'public/'.$path;
}

/**
 * Generate beautiful url with query string
 *
 * @param [type] $url
 * @param array $queryStrings
 * @return void
 */
function url($url, $queryStrings = [])
{
    if (count($queryStrings) > 0) {
        $queryString = http_build_query($queryStrings);
        return BASE_PATH.$url."?".$queryString;
    }
    return BASE_PATH.$url;
}

