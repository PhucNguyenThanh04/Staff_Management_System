<?php
require_once('app/Requests/BaseRequest.php');
require_once('app/Models/Employee.php');

class UserRequest extends BaseRequest
{
    public function validateCreate($data)
    {
        if (empty($data['email'])) {
            $this->errors['email'] = 'Email không được để trống';
        } else {
            $user_model = new Employee();
            $emailExists = $user_model->emailExists($data);
            if ($emailExists) {
                $this->errors['email'] = 'Email đã được sử dụng';
            }
        }

        if (empty($data['role'])) {
            $this->errors['role'] = 'Role không được để trống';
        }

        if (empty($data['password'])) {
            $this->errors['password'] = 'Mật khẩu không được để trống';
        }

        if (empty($data['password_confirmation'])) {
            $this->errors['password_confirmation'] = 'Xác nhận mật khẩu không được để trống';
        }

        if (!empty($data['password']) && !empty($data['password_confirmation']) && $data['password'] != $data['password_confirmation']) {
            $this->errors['password_confirmation'] = 'Nhập lại mật khẩu không chính xác';
        }

        return $this->errors;
    }

    public function validateUpdate($data)
    {
        if (empty($data['name'])) {
            $this->errors['name'] = 'Tên không được để trống';
        }

        if (empty($data['phone'])) {
            $this->errors['phone'] = 'SĐT không được để trống';
        }

        if (!empty($data['password']) && empty($data['password_confirmation'])) {
            $this->errors['password_confirmation'] = 'Xác nhận mật khẩu không được để trống';
        }

        if (!empty($data['password']) && !empty($data['password_confirmation']) && $data['password'] != $data['password_confirmation']) {
            $this->errors['password_confirmation'] = 'Nhập lại mật khẩu không chính xác';
        }
        return $this->errors;
    }
}
