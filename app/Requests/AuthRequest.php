<?php 
require_once('app/Requests/BaseRequest.php');
class AuthRequest extends BaseRequest 
{
    public function validateLogin($data) {
        if (empty($data['email'])) {
            $this->errors['email'] = 'Email không được để trống';
        }
        if (empty($data['password'])) {
            $this->errors['password'] = 'Password không được để trống';
        }
        return $this->errors;
    }
}
