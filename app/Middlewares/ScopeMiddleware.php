<?php 

require_once('app/Middlewares/BaseMiddleware.php');
require_once('core/Auth.php');

class ScopeMiddleware extends BaseMiddleware
{
    public function handle($parameters = [])
    {
        $user = Auth::getUser('mvc_employee');
        if (!isset($parameters['scopes'])) {
            redirect('');
        }
        $scopes = $parameters['scopes'];
        if (in_array($user['role'], $scopes)) {
            return true;
        }
        redirect('');
    }
}