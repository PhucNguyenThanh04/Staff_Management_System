<?php
require_once 'app/Controllers/Controller.php';
require_once 'app/Models/Asset.php';
require_once 'core/Flash.php';

class AssetController extends Controller
{
    protected $model;
    public function __construct()
    {
        $this->model = new Asset;
        $this->middleware('AuthMiddleware');
        $this->middleware('ScopeMiddleware', [
            'scopes' => [ employee_role_types['admin'], employee_role_types['nhansu'] ]
        ]);
    }

    public function index()
    {
        $search = "";
        if (isset($_GET['category']) && $_GET['category']) {
            $search .= " AND assets.category = " . $_GET['category'];
        }
        if (isset($_GET['status']) && $_GET['status']) {
            $search .= " AND assets.status = " . $_GET['status'];
        }
        if (isset($_GET['search']) && $_GET['search']) {
            $s = $_GET['search'];
            $search .= " AND (assets.code LIKE '%$s%' OR assets.name LIKE '%$s%')";
        }
        $sql = "SELECT * FROM assets WHERE true $search ORDER BY created_at DESC";
        $records = $this->model->paginationBase(10, $sql);
        return $this->view('pages/asset.php', $records);
    }

    public function create()
    {
        try {
            $data = $_POST;
            if (isset($data['id'])) unset($data['id']);
            // kiểm tra code trùng lặp
            if (!empty($data['code'])) {
                $exist = $this->model->getFirst("SELECT id FROM assets WHERE code = '{$data['code']}'");
                if ($exist) throw new Exception(LanguageHelper::trans('asset.code_already_exists'));
            }
            $result = $this->model->create($data);
            if ($result) {
                Flash::set('success', LanguageHelper::trans('asset.create_success'));
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
            if (!empty($data['code'])) {
                $exist = $this->model->getFirst("SELECT id FROM assets WHERE code = '{$data['code']}' AND id != {$data['id']}");
                if ($exist) throw new Exception(LanguageHelper::trans('asset.code_already_exists'));
            }
            $result = $this->model->update($data, $_POST['id']);
            if ($result) {
                Flash::set('success', LanguageHelper::trans('asset.update_success'));
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
                Flash::set('success', LanguageHelper::trans('asset.delete_success'));
            } else {
                Flash::set('error', LanguageHelper::trans('common.record_delete_error'));
            }
            $this->ajax();
        } catch (Exception $e) {
            Flash::set('error', LanguageHelper::trans('common.record_delete_error'));
            $this->ajax([], 500);
        }
    }

    public function search()
    {
        if (isset($_GET['q']) && $_GET['q']) {
            $s = $_GET['q'];
            $sql = "SELECT * FROM assets WHERE (code LIKE '%$s%' OR name LIKE '%$s%') ORDER BY created_at DESC";
            $data = $this->model->getAll($sql);
            return $this->ajax($data);
        }
        $this->ajax([], 500);
    }
}