<?php 

trait Middleware 
{
    public function middleware($middleware, $params = [])
    {
        require_once("app/Middlewares/{$middleware}.php");
        $middlewareClass = new $middleware();
        return call_user_func(array($middlewareClass, 'handle'), $params);
    }
}