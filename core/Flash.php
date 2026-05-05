<?php 

class Flash 
{
    public static function set($key, $message)
    {
        if (isset($message)) {
            $_SESSION[$key] = $message;
        }
        else {
            throw new Exception('Flash message không để trống');
        }
    }

    public static function get($key)
    {
        if (isset($_SESSION[$key])) {
            $message = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $message;
        }
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function hasChild($key, $child)
    {
        return isset($_SESSION[$key][$child]);
    }
}