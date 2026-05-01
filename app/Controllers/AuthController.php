<?php

require_once('app/Controllers/Controller.php');
require_once('core/Flash.php');
require_once('core/Auth.php');
require_once('app/Requests/UserRequest.php');
require_once('app/Requests/AuthRequest.php');
require_once('app/Models/Employee.php');


class AuthController extends Controller
{
    public function login()
    {
        return $this->view("pages/login.php", []);
    }

    public function handleLogin()
    {
        $request = new AuthRequest();
        $errors = $request->validateLogin($_POST);
        if (empty($errors)) {
            $user_model = new Employee();
            $data = $_POST;
            $user = $user_model->attempt($data);
            $remember = isset($_POST['remember']) ? true : false;
            if ($user) {
                Auth::setUser('mvc_employee', array_merge($user, ['remember' => $remember ? 1 : 0]), $remember);
                Flash::set('success', LanguageHelper::trans('noti.login_success'));
                return redirect('dashboard');
            } else {
                Flash::set('errors', ['wrong_email_password' => LanguageHelper::trans('noti.login_error')]);
                Flash::set('error', LanguageHelper::trans('noti.login_error'));
            }
            return back();
        }
        $errors['form_data'] = $_POST;
        Flash::set('errors', $errors);
        return back();
    }
    
    public function logout()
    {
        Auth::logout('mvc_employee');
        return redirect('auth/login');
    }
}
