<?php 
require_once ('core/Auth.php');

$helpers = scandir(__DIR__);
foreach ($helpers as $helper) {
    if(preg_match('/.php/', $helper, $matches)) {
        if ($helper != 'index.php') {
            require_once "helpers/$helper";
        }
    }
}

function isRoleAdmin()
{
    $user = Auth::getUser('mvc_employee');
    if (!$user) {
        return false;
    }
    return $user['role'] == employee_role_types['admin'];
}

function isRoleNhansu()
{
    $user = Auth::getUser('mvc_employee');
    if (!$user) {
        return false;
    }
    return $user['role'] == employee_role_types['nhansu'];
}

function isRoleKetoan()
{
    $user = Auth::getUser('mvc_employee');
    if (!$user) {
        return false;
    }
    return $user['role'] == employee_role_types['ketoan'];
}

function getRoleLabel($r) {
    $label = "";
    foreach (employee_roles as $role) {
        if ($role['value'] == $r) {
            $label = $role['label'];
        }
    }
    return $label;
}

function checkScope($page) {
    $user = Auth::getUser('mvc_employee');
    if (isset(employee_scopes[$user['role']])) {
        $scopes = employee_scopes[$user['role']];
        if (in_array($page, $scopes)) {
            return true;
        }
    }
    return false;
}

function isThisDayAWeekend($date) {
    $timestamp = strtotime($date);
    $weekday= date("l", $timestamp );

    if ($weekday =="Saturday" OR $weekday =="Sunday") { return true; } 
    else {return false; }
}