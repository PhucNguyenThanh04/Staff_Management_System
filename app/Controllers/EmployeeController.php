<?php

require_once 'app/Controllers/Controller.php';
require_once 'core/Flash.php';
require_once 'core/Auth.php';
require_once 'app/Models/Employee.php';
require_once 'app/Models/Department.php';

class EmployeeController extends Controller
{
    protected $model;
    public function __construct()
    {
        $this->model = new Employee;
        $this->middleware('AuthMiddleware');
        $this->middleware('ScopeMiddleware', [
            'scopes' => [
                employee_role_types['admin'],
                employee_role_types['nhansu'],
            ]
        ]);
    }

    public function index()
    {
        
        $sql = "SELECT employees.*, departments.name as department_name FROM employees left join departments on employees.department_id = departments.id where true";
        if (isset($_GET['status']) && $_GET['status']) {
            $sql .= " and employees.status = {$_GET['status']}";
        }
        if (isset($_GET['department_id']) && $_GET['department_id']) {
            $sql .= " and employees.department_id = {$_GET['department_id']}";
        }
        if (isset($_GET['name']) && $_GET['name']) {
            $sql .= " and employees.name like '%{$_GET['name']}%'";
        }
        if (isset($_GET['email']) && $_GET['email']) {
            $sql .= " and employees.email like '%{$_GET['email']}%'";
        }
        $sql .= " order by employees.created_at desc";
        $department_model = new Department;
        $departments = $department_model->all();
        $employees = $this->model->paginationBase(10, $sql);
        return $this->view('pages/employee.php', array_merge($employees, ['departments' => $departments]));
    }

    public function search()
    {
        if (isset($_GET['q']) && $_GET['q']) {
            $s = $_GET['q'];
            $sql = "SELECT * FROM employees where (email like '%$s%' or name like '%$s%') order by created_at desc";
            $data = $this->model->getAll($sql);
            return $this->ajax($data);
        }
        $this->ajax([], 500);
    }

    public function create()
    {
        try {
            $email = $_POST['email'];
            $existEmail = $this->model->emailExists($email);
            if ($existEmail) {
                throw new Exception(LanguageHelper::trans('employee.email_already_in_use'));
            }
            // Tự động tạo password mặc định là 123456
            $_POST['password'] = md5('123456');
            $result = $this->model->create($_POST);
            if ($result) {
                Flash::set('success', LanguageHelper::trans('employee.create_employee_success'));
            } else {
                Flash::set('error', LanguageHelper::trans('employee.create_employee_failed'));
            }
            $this->ajax();
        } catch (Exception $e) {
            $this->ajax(['message' => $e->getMessage()], 500);
        }
    }

    public function update()
    {
        try {
            $email = $_POST['email'];
            $existEmail = $this->model->emailExists($email);
            if ($existEmail && $existEmail['id'] != $_POST['id']) {
                throw new Exception(LanguageHelper::trans('employee.email_already_in_use'));
            }
            $data = $_POST;
            if (isset($data['birthday']) && !$data['birthday']) {
                unset($data['birthday']);
            }
            $result = $this->model->update($_POST, $_POST['id']);
            if ($result) {
                Flash::set('success', 'Cập nhật nhân viên thành công');
            } else {
                Flash::set('error', 'Cập nhật nhân viên thất bại');
            }
            $this->ajax();
        } catch (Exception $e) {
            $this->ajax(['message' => $e->getMessage()], 500);
        }
    }

    public function delete()
    {
        try {
            $id = $_GET['id'];
            $result = $this->model->delete($id);
            if ($result) {
                Flash::set('success', 'Xoá nhân viên thành công');
            } else {
                Flash::set('error', 'Xoá nhân viên thất bại');
            }
            $this->ajax();
        } catch (Exception $e) {
            Flash::set('error', 'Xoá nhân viên thất bại');
            $this->ajax([], 500);
        }
    }

    public function generatePassword()
    {
        try {
            if (!isRoleAdmin()) {
                throw new Exception('Chỉ quản trị viên mới có quyền đổi mật khẩu nhân viên.');
            }

            $password = $_POST['password'] ?? '';
            if (empty($password)) {
                throw new Exception('Mật khẩu không được để trống');
            }

            $id = $_POST['id'];
            $this->model->update(['password' => md5($password)], $id);
            $this->ajax([
                'message' => 'Đổi mật khẩu thành công',
            ]);
        } catch (Exception $e) {
            $this->ajax(['message' => $e->getMessage()], 500);
        }
    }
}
