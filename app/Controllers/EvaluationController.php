<?php
require_once 'app/Controllers/Controller.php';
require_once 'app/Models/Evaluation.php';
require_once 'app/Models/Employee.php';
require_once 'core/Flash.php';
require_once 'core/Auth.php';

class EvaluationController extends Controller
{
    protected $model;
    public function __construct()
    {
        $this->model = new Evaluation;
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
            $search = " AND evaluations.employee_id = " . $user['id'];
        } else {
            if (isset($_GET['employee_id']) && $_GET['employee_id']) {
                $search .= " AND evaluations.employee_id = " . $_GET['employee_id'];
            }
            if (isset($_GET['eval_type']) && $_GET['eval_type']) {
                $search .= " AND evaluations.eval_type = " . $_GET['eval_type'];
            }
            if (isset($_GET['period']) && $_GET['period']) {
                $period = $_GET['period'];
                $search .= " AND evaluations.period = '$period'";
            }
        }

        $sql = "
            SELECT evaluations.*,
                employees.name AS employee_name,
                employees.email AS employee_email
            FROM evaluations
            INNER JOIN employees ON employees.id = evaluations.employee_id
            WHERE true $search
            ORDER BY evaluations.eval_date DESC
        ";
        $evaluationRecords = $this->model->paginationBase(10, $sql);
        return $this->view('pages/evaluation.php', $evaluationRecords);
    }

    public function create()
    {
        try {
            $data = $_POST;
            if (isset($data['id'])) unset($data['id']);
            // tự động điền reviewer_id nếu chưa có
            $data['reviewer_id'] = $data['reviewer_id'] ?? Auth::getUser('mvc_employee')['id'];
            $result = $this->model->create($data);
            if ($result) {
                Flash::set('success', LanguageHelper::trans('evaluation.create_success'));
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
            $result = $this->model->update($data, $_POST['id']);
            if ($result) {
                Flash::set('success', LanguageHelper::trans('evaluation.update_success'));
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
                Flash::set('success', LanguageHelper::trans('evaluation.delete_success'));
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