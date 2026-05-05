<?php

require_once 'app/Controllers/Controller.php';
require_once 'app/Models/Attendance.php';
require_once 'app/Models/Employee.php';
require_once 'core/Flash.php';
require_once 'core/Auth.php';

class AttendanceController extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = new Attendance;
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
        $search = "";
        $user = Auth::getUser('mvc_employee');
        if ($user['role'] == employee_role_types['nhanvien']) {
            $search = " AND attendances.employee_id = " . $user['id'];
        } else {
            if (isset($_GET['employee_id']) && $_GET['employee_id']) {
                $search .= " AND attendances.employee_id = " . $_GET['employee_id'];
            }
            if (isset($_GET['attendance_date']) && $_GET['attendance_date']) {
                $attendance_date = $_GET['attendance_date'];
                $search .= " AND attendances.attendance_date = '$attendance_date'";
            }
            if (isset($_GET['by_month']) && $_GET['by_month']) {
                $by_month = $_GET['by_month'];
                $search .= " and DATE_FORMAT(attendance_date, '%Y-%m') = '$by_month'";
            }
        }
        $sql = "
            SELECT attendances.*,
                employees.name AS employee_name,
                employees.email AS employee_email
            FROM attendances
            INNER JOIN employees ON employees.id = attendances.employee_id
            WHERE true $search
            ORDER BY attendances.attendance_date DESC
        ";
        $attendanceRecords = $this->model->paginationBase(10, $sql);
        if (isset($_GET['employee_id']) && $_GET['employee_id']) {
            $empoyee_model = new Employee();
            $empoyee = $empoyee_model->find($_GET['employee_id']);
            $attendanceRecords['employee'] = $empoyee;
        }
        return $this->view('pages/attendance.php', $attendanceRecords);
    }

    public function sumByMonth()
    {
        try {
            $payrollMonth = $_POST['payroll_month'];
            $employeeId = $_POST['employee_id'];
            $result = $this->model->getFirst("
                SELECT sum(work_point) as 'sum_work_point'
                FROM attendances
                WHERE
                    employee_id = $employeeId and
                    DATE_FORMAT(attendance_date, '%Y-%m') = '$payrollMonth';
            ");
            $sum_work_point = $result['sum_work_point'];
            return $this->ajax([
                'sum_work_point' => $sum_work_point ? floatval($sum_work_point) : 0
            ]);
        } catch (Exception $e) {
            $this->ajax(['error' => $e->getMessage()], 500);
        }
    }

    private function validate_and_calculate_data_attendance($data) {
        $check_in = $data['check_in'];
        $check_out = $data['check_out'];

        $work_start_time = work_start_time;
        $work_end_time = work_end_time;
        $work_start = DateTime::createFromFormat('H:i', $work_start_time);
        $work_end = DateTime::createFromFormat('H:i', $work_end_time);

        $check_in_time = DateTime::createFromFormat('H:i', $check_in);
        $check_out_time = DateTime::createFromFormat('H:i', $check_out);

        if ($check_in_time === false) {
            $check_in_time = DateTime::createFromFormat('H:i:s', $check_in);
            if ($check_in_time === false ) {
                throw new Exception('Params không hợp lệ');
            }
        }
        if ($check_out_time === false) {
            $check_out_time = DateTime::createFromFormat('H:i:s', $check_out);
            if ($check_out_time === false) {
                throw new Exception('Params không hợp lệ');
            }
        }

        if ($check_out_time < $check_in_time) {
            throw new Exception('Thời gian checkin cần trước checkout');
        }

        $sleep_hours = 1.5;
        $interval = $check_in_time->diff($check_out_time);
        $working_hours = $interval->h + ($interval->i / 60); // Số giờ làm việc tính theo giờ và phút
        if ($working_hours > $sleep_hours) {
            $working_hours -= $sleep_hours;
        }

        $work_point = 0;
        if ($check_in_time <= $work_start && $check_out_time >= $work_end) {
            $work_point = 1;
        }
        $check_in_diff = $check_in_time->diff($work_start);
        $check_out_diff = $work_end->diff($check_out_time);
        if ($check_in_time <= $work_start && ($check_out_time < $work_end || !$check_out)) {
            $work_point = 0.5;
        }
        if ($check_in_time > $work_start && $check_in_diff->h >= 1 && $check_out_time >= $work_end) {
            $work_point = 0.5;
        }
        if ($check_in_time > $work_start && $check_in_diff->h >= 1 && $check_out_time < $work_end) {
            $work_point = 0;
        }

        $data['work_point'] = $work_point;
        $data['working_hours'] = $working_hours;
        return $data;
    }   

    public function create()
    {
        try {
            $data = $_POST;
            if (isset($data['id'])) {
                unset($data['id']);
            }
            $parse_data = $this->validate_and_calculate_data_attendance($data);
            $result = $this->model->create($parse_data);
            if ($result) {
                Flash::set('success', LanguageHelper::trans('common.record_create_success'));
            } else {
                Flash::set('error', LanguageHelper::trans('common.record_create_error'));
            }
            $this->ajax();
        } catch (Exception $e) {
            Flash::set('error', $e->getMessage() ? $e->getMessage() : LanguageHelper::trans('common.record_create_error'));
            $this->ajax(['error' => $e->getMessage()], 500);
        }
    }

    public function update()
    {
        try {
            $parse_data = $this->validate_and_calculate_data_attendance($_POST);
            $result = $this->model->update($parse_data, $_POST['id']);
            if ($result) {
                Flash::set('success', LanguageHelper::trans('common.record_update_success'));
            } else {
                Flash::set('error', LanguageHelper::trans('common.record_update_error'));
            }
            $this->ajax();
        } catch (Exception $e) {
            Flash::set('error', $e->getMessage() ? $e->getMessage() : LanguageHelper::trans('common.record_update_error'));
            $this->ajax(['error' => $e->getMessage()], 500);
        }
    }

    public function delete()
    {
        try {
            $id = $_GET['id'];
            if (!is_numeric($id)) {
                throw new Exception('id not number');
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
