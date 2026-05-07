<?php
require_once 'app/Controllers/Controller.php';
require_once 'app/Models/RewardDiscipline.php';
require_once 'app/Models/Employee.php';
require_once 'core/Flash.php';
require_once 'core/Auth.php';

class RewardDisciplineController extends Controller
{
    protected $model;
    public function __construct()
    {
        $this->model = new RewardDiscipline;
        $this->middleware('AuthMiddleware');
        $this->middleware('ScopeMiddleware', [
            'scopes' => [
                employee_role_types['admin'],
                employee_role_types['nhansu'],
                employee_role_types['nhanvien'],
            ]
        ]);
    }

    public function index()
    {
        $search = "";
        $user = Auth::getUser('mvc_employee');
        if ($user['role'] == employee_role_types['nhanvien']) {
            $search = " AND rewards_disciplines.employee_id = " . $user['id'];
        } else {
            if (isset($_GET['employee_id']) && $_GET['employee_id']) {
                $search .= " AND rewards_disciplines.employee_id = " . $_GET['employee_id'];
            }
            if (isset($_GET['rd_type']) && $_GET['rd_type']) {
                $search .= " AND rewards_disciplines.rd_type = " . $_GET['rd_type'];
            }
            if (isset($_GET['status']) && $_GET['status']) {
                $status = $_GET['status'];
                $search .= " AND rewards_disciplines.status = '$status'";
            }
        }
        $sql = "
            SELECT rewards_disciplines.*,
                employees.name AS employee_name,
                employees.email AS employee_email
            FROM rewards_disciplines
            INNER JOIN employees ON employees.id = rewards_disciplines.employee_id
            WHERE true $search
            ORDER BY rewards_disciplines.effective_date DESC
        ";
        $records = $this->model->paginationBase(10, $sql);
        return $this->view('pages/reward_discipline.php', $records);
    }

    public function create()
    {
        try {
            $data = $_POST;
            if (isset($data['id'])) unset($data['id']);
            if (empty($data['status'])) $data['status'] = 'pending';
            $result = $this->model->create($data);
            if ($result) {
                Flash::set('success', LanguageHelper::trans('reward_discipline.create_success'));
            } else {
                Flash::set('error', LanguageHelper::trans('common.record_create_error'));
            }
            $this->ajax();
        } catch (Exception $e) {
            Flash::set('error', $e->getMessage());
            $this->ajax(['error' => $e->getMessage()], 500);
        }
    }

    public function update()
    {
        try {
            $data = $_POST;
            // nếu đang cập nhật phê duyệt thì có thể set approved_by
            if (!empty($data['status']) && $data['status'] == 'approved' && empty($data['approved_by'])) {
                $data['approved_by'] = Auth::getUser('mvc_employee')['id'];
            }
            $result = $this->model->update($data, $_POST['id']);
            if ($result) {
                Flash::set('success', LanguageHelper::trans('reward_discipline.update_success'));
            } else {
                Flash::set('error', LanguageHelper::trans('common.record_update_error'));
            }
            $this->ajax();
        } catch (Exception $e) {
            Flash::set('error', $e->getMessage());
            $this->ajax(['error' => $e->getMessage()], 500);
        }
    }

    public function delete()
    {
        try {
            $id = $_GET['id'];
            if (!is_numeric($id)) throw new Exception('id not number');
            $result = $this->model->delete($id);
            if ($result) {
                Flash::set('success', LanguageHelper::trans('reward_discipline.delete_success'));
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