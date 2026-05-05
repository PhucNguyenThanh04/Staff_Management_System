<?php

require_once 'app/Controllers/Controller.php';
require_once 'app/Models/Department.php';
require_once 'app/Models/Employee.php';
require_once 'core/Flash.php';

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('AuthMiddleware');
    }

    public function index()
    {
        $employee_model = new Employee;
        $department_model = new Department;
        $departments = $department_model->all();
        $user = Auth::getUser('mvc_employee');
        $profile = $employee_model->find($user['id']);
        return $this->view('pages/profile.php', [
            'profile' => $profile,
            'departments' => $departments,
        ]);
    }

    public function update()
    {
        try {
            $employee_model = new Employee;
            $user = Auth::getUser('mvc_employee');
            $profile = $employee_model->find($user['id']);

            $params = $_POST;
            $password = $params['password'];
            $data = [];
            if (isRoleAdmin()) {
                $data['email'] = $params['email'];
                $data['department_id'] = $params['department_id'];
            }
            $data['name'] = $params['name'];
            $data['birthday'] = $params['birthday'];
            $data['address'] = $params['address'];
            $data['phone_number'] = $params['phone_number'];
            $data['gender'] = $params['gender'];
            $data['cccd'] = $params['cccd'];
            if ($password) {
                $data['password'] = md5($password);
            }
            $result = $employee_model->update($data, $user['id']);
            if ($result) {
                $remember = $user['remember'];
                Auth::setUser('mvc_employee', array_merge($result, ['remember' => $remember ? 1 : 0]), $remember);
                Flash::set('success', 'Cập nhật thông tin cá nhân thành công');
            } else {
                throw new Exception('Cập nhật thông tin cá nhân thất bại');
            }
            return back('/profile/update');
        } catch (Exception $e) {
            Flash::set('error', $e->getMessage());
            return back('/profile/update');
        }
    }
}
