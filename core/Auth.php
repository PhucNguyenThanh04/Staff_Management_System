<?php 

class Auth
{
    public static function setUser($key, $user, $useCookies = false)
    {
        if ($useCookies) {
            setcookie($key, serialize($user), time() + 3600, '/');
        }
        else {
            $_SESSION[$key] = $user;
        }
    }

    public static function loggedIn($key)
    {
        if (isset($_SESSION[$key])) {
            return true;
        } 

        if (isset($_COOKIE[$key])) {
            return true;
        }

        return false;
    }

    public static function getUser($key)
    {
        // print_r($_COOKIE['user']);die();
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        if (isset($_COOKIE[$key])) {
            return unserialize($_COOKIE[$key]);
        }
        return false;
    }

    public static function logout($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }

        if (isset($_COOKIE[$key])) {
            setcookie($key, '', time()-1, '/');
        }
    }
}