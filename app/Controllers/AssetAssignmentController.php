<?php
require_once 'app/Controllers/Controller.php';
require_once 'app/Models/AssetAssignment.php';
require_once 'app/Models/Asset.php';
require_once 'app/Models/Employee.php';
require_once 'core/Flash.php';

class AssetAssignmentController extends Controller
{
    protected $model;
    public function __construct()
    {
        $this->model = new AssetAssignment;
        $this->middleware('AuthMiddleware');
        $this->middleware('ScopeMiddleware', [
            'scopes' => [ employee_role_types['admin'], employee_role_types['nhansu'] ]
        ]);
    }

    public function index()
    {
        $search = "";
        if (isset($_GET['asset_id']) && $_GET['asset_id']) {
            $search .= " AND asset_assignments.asset_id = " . $_GET['asset_id'];
        }
        if (isset($_GET['employee_id']) && $_GET['employee_id']) {
            $search .= " AND asset_assignments.employee_id = " . $_GET['employee_id'];
        }
        if (isset($_GET['status']) && $_GET['status']) {
            if ($_GET['status'] == 'active') {
                $search .= " AND asset_assignments.return_date IS NULL";
            } elseif ($_GET['status'] == 'returned') {
                $search .= " AND asset_assignments.return_date IS NOT NULL";
            }
        }

        $sql = "
            SELECT asset_assignments.*,
                assets.name AS asset_name, assets.code AS asset_code,
                employees.name AS employee_name, employees.email AS employee_email,
                assigner.name AS assigned_by_name
            FROM asset_assignments
            INNER JOIN assets ON assets.id = asset_assignments.asset_id
            INNER JOIN employees ON employees.id = asset_assignments.employee_id
            LEFT JOIN employees AS assigner ON assigner.id = asset_assignments.assigned_by
            WHERE true $search
            ORDER BY asset_assignments.assign_date DESC
        ";
        $records = $this->model->paginationBase(10, $sql);
        return $this->view('pages/asset_assignment.php', $records);
    }

    public function create()
    {
        try {
            $data = $_POST;
            if (isset($data['id'])) unset($data['id']);
            $data['assigned_by'] = Auth::getUser('mvc_employee')['id'];
            $assetId = $data['asset_id'];

            // Kiểm tra asset có đang rảnh không
            $assetModel = new Asset();
            $asset = $assetModel->find($assetId);
            if (!$asset) throw new Exception('Tài sản không tồn tại');
            if ($asset['status'] != 1) {
                throw new Exception('Tài sản không ở trạng thái sẵn sàng để cấp phát');
            }

            // Kiểm tra asset chưa có ai mượn chưa trả
            $activeAssignment = $this->model->getFirst("SELECT id FROM asset_assignments WHERE asset_id = $assetId AND return_date IS NULL");
            if ($activeAssignment) {
                throw new Exception('Tài sản đang được cấp phát cho người khác');
            }

            // Cập nhật trạng thái asset sang đang cấp phát
            $assetModel->update(['status' => 2], $assetId);

            $result = $this->model->create($data);
            if ($result) {
                Flash::set('success', LanguageHelper::trans('asset_assignment.create_success'));
            } else {
                // rollback status nếu thất bại
                $assetModel->update(['status' => 1], $assetId);
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
            $id = $data['id'];
            // Lấy thông tin assignment hiện tại
            $assignment = $this->model->find($id);
            if (!$assignment) throw new Exception('Bản ghi không tồn tại');

            // Nếu đang cập nhật trả tài sản (điền return_date)
            if (!empty($data['return_date']) && empty($assignment['return_date'])) {
                // Cập nhật trạng thái tài sản về sẵn sàng
                $assetModel = new Asset();
                $assetModel->update(['status' => 1], $assignment['asset_id']);
            }

            $result = $this->model->update($data, $id);
            if ($result) {
                Flash::set('success', LanguageHelper::trans('asset_assignment.update_success'));
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
            $assignment = $this->model->find($id);
            if ($assignment && empty($assignment['return_date'])) {
                // Nếu xóa bản ghi đang active, trả asset về sẵn sàng
                $assetModel = new Asset();
                $assetModel->update(['status' => 1], $assignment['asset_id']);
            }
            $result = $this->model->delete($id);
            if ($result) {
                Flash::set('success', LanguageHelper::trans('asset_assignment.delete_success'));
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