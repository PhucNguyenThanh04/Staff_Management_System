<?php

require_once 'app/Controllers/Controller.php';
require_once 'app/Models/Department.php';
require_once 'app/Models/Employee.php';

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('AuthMiddleware');
        $this->middleware('ScopeMiddleware', [
            'scopes' => [
                employee_role_types['admin'],
                employee_role_types['nhansu'],
                employee_role_types['ketoan'],
                employee_role_types['nhanvien'],
            ]
        ]);
    }

    public function index()
    {
        $m_department = new Department;
        $m_employee = new Employee;
        $data = [];
        $data['department_count'] = count($m_department->all());
        $data['employee_count'] = $m_employee->getAll("SELECT COUNT(employees.id) as count, departments.name as department_name, employees.department_id as department_id from employees INNER JOIN departments ON departments.id = employees.department_id where true GROUP BY employees.department_id, departments.name");
        $data['dilam_count'] = count($m_employee->getAll("SELECT * from employees where status = 1"));
        $data['nghiviec_count'] = count($m_employee->getAll("SELECT * from employees where status = 2"));
        return $this->view('pages/dashboard.php', $data);
    }
}
