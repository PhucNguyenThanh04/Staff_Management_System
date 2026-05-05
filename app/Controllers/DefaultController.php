<?php

require_once 'app/Controllers/Controller.php';

class DefaultController extends Controller
{
    public function __construct()
    {
        $this->middleware('AuthMiddleware');
    }

    public function index()
    {
        return redirect('dashboard');
    }
}
