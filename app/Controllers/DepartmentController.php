<?php

require_once 'app/Controllers/Controller.php';
require_once 'app/Models/Department.php';
require_once 'core/Flash.php';

class DepartmentController extends Controller
{
    protected $model;
    public function __construct()
    {
        $this->model = new Department;
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
        $sql = "SELECT * FROM departments order by created_at desc";
        $departments = $this->model->paginationBase(5, $sql);
        return $this->view('pages/department.php', $departments);
    }

    public function search()
    {
        if (isset($_GET['q']) && $_GET['q']) {
            $s = $_GET['q'];
            $sql = "SELECT * FROM departments where departments.name like '%$s%' order by created_at desc";
            $data = $this->model->getAll($sql);
            return $this->ajax($data);
        }
        $this->ajax([], 500);
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
