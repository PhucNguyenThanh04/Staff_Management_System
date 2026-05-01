<?php 

/**
 * Navigate to public path to get files inside
 *
 * @param [type] $path
 * @return void
 */
function dateFromStr($str, $format)
{
    $date=date_create($str);
    return date_format($date, $format);
}

function daysInMonth($month, $year = 0)
{
    if ($year == 0) {
        $year = new DateTime();
        $year = $year->format('Y');
    }
    $list = array();
    for ($d = 1; $d <= 31; $d++) {
        $time = mktime(12, 0, 0, $month, $d, $year);
        if (date('m', $time) == $month) {
            $list[] = date('Y-m-d', $time);
        }
    }
    return $list;
}

function now()
{
    return date("Y-m-d H:i:s");
}
