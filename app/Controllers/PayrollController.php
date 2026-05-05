<?php

require_once 'app/Controllers/Controller.php';
require_once 'app/Models/Payroll.php';
require_once 'core/Flash.php';
require_once('core/Auth.php');

class PayrollController extends Controller
{
    protected $model;
    public function __construct()
    {
        $this->model = new Payroll;
        $this->middleware('AuthMiddleware');
        $this->middleware('ScopeMiddleware', [
            'scopes' => [
                employee_role_types['admin'],
                employee_role_types['ketoan'],
                employee_role_types['nhanvien'],
            ]
        ]);
    }

    public function index()
    {
        if (isset($_GET['statistic_by_time'])) {
            $statistic_by_time = $_GET['statistic_by_time'];
            $sql = "
                SELECT payroll_details.*,
                    employees.name as employee_name,
                    employees.email as employee_email
                FROM payroll_details
                    inner join employees on employees.id = payroll_details.employee_id
                where payroll_details.payroll_month = '$statistic_by_time'
                order by payroll_details.created_at desc
            ";
            $statistics = $this->model->getAll($sql);
            return $this->view('pages/payroll.php', [
                'statistics' => $statistics
            ]);
        }

        $search = "";
        $user = Auth::getUser('mvc_employee');
        if ($user['role'] == employee_role_types['nhanvien']) {
            $search = " and employee_id = " . $user['id'];
        } else {
            if (isset($_GET['search'])) {
                $s = $_GET['search'];
                $search = "and (employees.name like '%$s%' or employees.email like '%$s%')";
            }
        }
        $sql = "
            SELECT payrolls.*,
                employees.name as employee_name,
                employees.email as employee_email
            FROM payrolls
                inner join employees on employees.id = payrolls.employee_id
            WHERE true $search
            order by payrolls.created_at desc
        ";
        $payrolls = $this->model->paginationBase(5, $sql);
        return $this->view('pages/payroll.php', $payrolls);
    }

    public function create()
    {
        try {
            $result = $this->model->create($_POST);
            if ($result) {
                Flash::set('success', LanguageHelper::trans('common.record_create_success'));
            } else {
                Flash::set('error', LanguageHelper::trans('common.record_create_error'));
            }
            $this->ajax();
        } catch (Exception $e) {
            Flash::set('error', LanguageHelper::trans('common.record_create_error'));
            $this->ajax([], 500);
        }
    }

    public function update()
    {
        try {
            $result = $this->model->update($_POST, $_POST['id']);
            if ($result) {
                Flash::set('success', LanguageHelper::trans('common.record_update_success'));
            } else {
                Flash::set('error', LanguageHelper::trans('common.record_update_error'));
            }
            $this->ajax();
        } catch (Exception $e) {
            Flash::set('error', LanguageHelper::trans('common.record_update_error'));
            $this->ajax([], 500);
        }
    }

    public function delete()
    {
        try {
            $id = $_GET['id'];
            if (!is_numeric($id)) {
                throw new Exception('id not number');
            }
            $payrollDetails = $this->model->getAll("select * from payroll_details where payroll_id = $id");
            if (count($payrollDetails) > 0) {
                throw new Exception('PayrollDetails existed, can\'t delete');
            }
            $result = $this->model->delete($id);
            if ($result) {
                Flash::set('success', LanguageHelper::trans('common.record_delete_success'));
            } else {
                Flash::set('error', LanguageHelper::trans('common.record_delete_error'));
            }
            $this->ajax();
        } catch (Exception $e) {
            Flash::set('error', LanguageHelper::trans('common.record_delete_error'));
            $this->ajax([], 500);
        }
    }
}
