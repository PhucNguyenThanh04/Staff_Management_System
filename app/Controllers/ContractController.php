<?php

require_once 'app/Controllers/Controller.php';
require_once 'app/Models/Contract.php';
require_once 'core/Flash.php';

class ContractController extends Controller
{
    protected $model;
    public function __construct()
    {
        $this->model = new Contract;
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
        $search = "";
        if (isset($_GET['search'])) {
            $s = $_GET['search'];
            $search = "and (employees.name like '%$s%' or employees.email like '%$s%')";
        }
        $sql = "
            SELECT contracts.*,
                employees.name as employee_name,
                employees.email as employee_email
            FROM contracts
                inner join employees on employees.id = contracts.employee_id
            WHERE true $search
            order by created_at desc
        ";

        $contracts = $this->model->paginationBase(5, $sql);
        return $this->view('pages/contract.php', $contracts);
    }

    public function search()
    {
        if (isset($_GET['q']) && $_GET['q']) {
            $s = $_GET['q'];
            $sql = "SELECT * FROM contracts where contracts.name like '%$s%' order by created_at desc";
            $data = $this->model->getAll($sql);
            return $this->ajax($data);
        }
        $this->ajax([], 500);
    }

    public function create()
    {
        try {
            $cleaned = array_filter($_POST, function ($value) {
                return !(is_string($value) && trim($value) === '');
            });
            $result = $this->model->create($cleaned);
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
