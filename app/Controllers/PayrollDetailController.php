<?php

require_once 'app/Controllers/Controller.php';
require_once 'app/Models/PayrollDetail.php';
require_once 'core/Flash.php';
require_once('core/Auth.php');

class PayrollDetailController extends Controller
{
    protected $model;
    public function __construct()
    {
        $this->model = new PayrollDetail;
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
        try {
            if (!isset($_GET['payroll_id'])) {
                throw new Exception('payroll_id not found');
            }
            $payrollId = $_GET['payroll_id'];
            $payroll = $this->model->getFirst("select * from payrolls where id = $payrollId");
            if (!$payroll) {
                throw new Exception('payroll not found');
            }
            $user = Auth::getUser('mvc_employee');
            if ($payroll['employee_id'] != $user['id'] && !isRoleAdmin() && !isRoleKetoan()) {
                throw new Exception('payroll does not belong to employee');
            }
            $employee = $this->model->getFirst("select * from employees where id = " . $payroll['employee_id']);
            $sql = "
                select *
                from payroll_details
                where payroll_id = " . $payroll['id'] . " order by payroll_details.payment_date desc";
            $payroll_details = $this->model->paginationBase(5, $sql);
            $payroll_details['employee'] = $employee;
            $payroll_details['payroll'] = $payroll;
            return $this->view('pages/payroll_detail.php', $payroll_details);
        } catch (Exception $e) {
            Flash::set('error', $e->getMessage());
            return redirect('');
        }
    }

    public function create()
    {
        try {
            if (!isset($_POST['salary']) || !isset($_POST['bonus']) || !isset($_POST['deductions']) || !isset($_POST['insurance']) || !isset($_POST['employee_id']) || !isset($_POST['payroll_id']) || !isset($_POST['payment_date'])) {
                throw new Exception('');
            }
            $salary = $_POST['salary'];
            $bonus = $_POST['bonus'];
            $deductions = $_POST['deductions'];
            $insurance = $_POST['insurance'];
            $net_salary = floatval($salary) + floatval($bonus) - floatval($deductions) - floatval($insurance);
            $_POST['net_salary'] = $net_salary;
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
            if (!isset($_POST['salary']) || !isset($_POST['bonus']) || !isset($_POST['deductions']) || !isset($_POST['insurance']) || !isset($_POST['employee_id']) || !isset($_POST['payroll_id']) || !isset($_POST['payment_date'])) {
                throw new Exception('');
            }
            $salary = $_POST['salary'];
            $bonus = $_POST['bonus'];
            $deductions = $_POST['deductions'];
            $insurance = $_POST['insurance'];
            $net_salary = floatval($salary) + floatval($bonus) - floatval($deductions) - floatval($insurance);
            $_POST['net_salary'] = $net_salary;
            // dd($_POST);
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
