<?php 

abstract class BaseMiddleware
{
    public abstract function handle($parameters);
}