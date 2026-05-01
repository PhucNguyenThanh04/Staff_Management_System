<?php 

require_once('app/Middlewares/BaseMiddleware.php');
require_once('core/Auth.php');

class AuthMiddleware extends BaseMiddleware
{
    public function handle($parameters = null)
    {
        if (!Auth::loggedIn('mvc_employee')) {
            redirect('auth/login');
            exit;
        }
        return true;
    }
}