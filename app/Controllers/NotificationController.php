<?php

require_once 'app/Controllers/Controller.php';
require_once 'app/Models/Notification.php';
require_once 'app/Models/NotificationRead.php';
require_once 'core/Auth.php';
require_once 'core/Flash.php';

class NotificationController extends Controller
{
    protected $model;
    protected $notificationReadModel;

    public function __construct()
    {
        $this->model = new Notification;
        $this->notificationReadModel = new NotificationRead;
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

    private function canManageNotifications()
    {
        return isRoleAdmin() || isRoleNhansu();
    }

    private function visibilityCondition($user)
    {
        if ($this->canManageNotifications()) {
            return "1=1";
        }

        $userId = intval($user['id']);
        $role = intval($user['role']);
        $departmentId = isset($user['department_id']) ? intval($user['department_id']) : 0;

        $departmentCondition = "";
        if ($departmentId > 0) {
            $departmentCondition = " OR (n.target_type = 3 AND n.target_value = '$departmentId')";
        }

        return "(
            n.target_type = 1
            OR (n.target_type = 2 AND n.target_value = '$role')
            $departmentCondition
            OR (n.target_type = 4 AND n.target_value = '$userId')
        )";
    }

    private function normalizeNotificationData($params)
    {
        $title = isset($params['title']) ? trim($params['title']) : '';
        $content = isset($params['content']) ? trim($params['content']) : '';
        $targetType = isset($params['target_type']) ? intval($params['target_type']) : 1;
        $targetValue = isset($params['target_value']) ? trim($params['target_value']) : '';

        if (!$title) {
            throw new Exception(LanguageHelper::trans('notification.validate_title'));
        }
        if (!$content) {
            throw new Exception(LanguageHelper::trans('notification.validate_content'));
        }
        if (!in_array($targetType, [1, 2, 3, 4])) {
            throw new Exception(LanguageHelper::trans('notification.validate_target_type'));
        }
        if ($targetType !== 1 && !$targetValue) {
            throw new Exception(LanguageHelper::trans('notification.validate_target_value'));
        }
        if ($targetType == 1) {
            $targetValue = null;
        } else {
            if (!is_numeric($targetValue)) {
                throw new Exception(LanguageHelper::trans('notification.validate_target_value'));
            }
            $targetValue = strval(intval($targetValue));
        }

        return [
            'title' => $title,
            'content' => $content,
            'target_type' => $targetType,
            'target_value' => $targetValue,
            'is_pinned' => isset($params['is_pinned']) && intval($params['is_pinned']) == 1 ? 1 : 0,
            'is_active' => isset($params['is_active']) ? (intval($params['is_active']) == 1 ? 1 : 0) : 1
        ];
    }

    public function index()
    {
        $user = Auth::getUser('mvc_employee');
        $userId = intval($user['id']);
        $isManager = $this->canManageNotifications();
        $visibilityCondition = $this->visibilityCondition($user);

        $sql = "
            SELECT n.*,
                sender.name as sender_name,
                departments.name as target_department_name,
                target_employees.name as target_employee_name,
                nr.read_at as read_at,
                (
                    SELECT COUNT(*)
                    FROM notification_reads read_rows
                    WHERE read_rows.notification_id = n.id
                ) as read_count
            FROM notifications n
            LEFT JOIN employees sender ON sender.id = n.sender_id
            LEFT JOIN departments ON n.target_type = 3 AND departments.id = n.target_value
            LEFT JOIN employees target_employees ON n.target_type = 4 AND target_employees.id = n.target_value
            LEFT JOIN notification_reads nr ON nr.notification_id = n.id AND nr.employee_id = $userId
            WHERE $visibilityCondition
        ";

        if (!$isManager) {
            $sql .= " AND n.is_active = 1";
        }

        if (isset($_GET['q']) && $_GET['q']) {
            $q = $_GET['q'];
            $sql .= " AND (n.title LIKE '%$q%' OR n.content LIKE '%$q%')";
        }

        if (isset($_GET['target_type']) && $_GET['target_type'] !== '') {
            $targetType = intval($_GET['target_type']);
            $sql .= " AND n.target_type = $targetType";
        }

        if (isset($_GET['only_unread']) && $_GET['only_unread'] == 1) {
            $sql .= " AND nr.read_at IS NULL";
        }

        if ($isManager && isset($_GET['is_active']) && $_GET['is_active'] !== '') {
            $isActive = intval($_GET['is_active']) == 1 ? 1 : 0;
            $sql .= " AND n.is_active = $isActive";
        }

        $sql .= " ORDER BY n.is_pinned DESC, n.created_at DESC";

        $notifications = $this->model->paginationBase(10, $sql);
        return $this->view('pages/notification.php', array_merge($notifications, [
            'can_manage' => $isManager
        ]));
    }

    public function create()
    {
        try {
            if (!$this->canManageNotifications()) {
                throw new Exception(LanguageHelper::trans('notification.permission_denied'));
            }
            $data = $this->normalizeNotificationData($_POST);
            $data['sender_id'] = Auth::getUser('mvc_employee')['id'];
            $result = $this->model->create($data);
            if ($result) {
                Flash::set('success', LanguageHelper::trans('notification.create_success'));
            } else {
                Flash::set('error', LanguageHelper::trans('common.record_create_error'));
            }
            $this->ajax();
        } catch (Exception $e) {
            Flash::set('error', $e->getMessage());
            $this->ajax(['message' => $e->getMessage()], 500);
        }
    }

    public function update()
    {
        try {
            if (!$this->canManageNotifications()) {
                throw new Exception(LanguageHelper::trans('notification.permission_denied'));
            }
            if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
                throw new Exception('id not number');
            }
            $id = intval($_POST['id']);
            $found = $this->model->find($id);
            if (!$found) {
                throw new Exception(LanguageHelper::trans('notification.not_found'));
            }
            $data = $this->normalizeNotificationData($_POST);
            $result = $this->model->update($data, $id);
            if ($result) {
                Flash::set('success', LanguageHelper::trans('notification.update_success'));
            } else {
                Flash::set('error', LanguageHelper::trans('common.record_update_error'));
            }
            $this->ajax();
        } catch (Exception $e) {
            Flash::set('error', $e->getMessage());
            $this->ajax(['message' => $e->getMessage()], 500);
        }
    }

    public function delete()
    {
        try {
            if (!$this->canManageNotifications()) {
                throw new Exception(LanguageHelper::trans('notification.permission_denied'));
            }
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!is_numeric($id)) {
                throw new Exception('id not number');
            }
            $result = $this->model->delete(intval($id));
            if ($result) {
                Flash::set('success', LanguageHelper::trans('notification.delete_success'));
            } else {
                Flash::set('error', LanguageHelper::trans('common.record_delete_error'));
            }
            $this->ajax();
        } catch (Exception $e) {
            Flash::set('error', LanguageHelper::trans('common.record_delete_error'));
            $this->ajax(['message' => $e->getMessage()], 500);
        }
    }

    public function markRead()
    {
        try {
            if (!isset($_POST['notification_id']) || !is_numeric($_POST['notification_id'])) {
                throw new Exception('notification_id not number');
            }
            $notificationId = intval($_POST['notification_id']);
            $user = Auth::getUser('mvc_employee');
            $userId = intval($user['id']);

            $visibilityCondition = $this->visibilityCondition($user);
            $activeCondition = $this->canManageNotifications() ? "1=1" : "n.is_active = 1";
            $found = $this->model->getFirst("
                SELECT n.id
                FROM notifications n
                WHERE n.id = $notificationId AND $visibilityCondition AND $activeCondition
            ");
            if (!$found) {
                throw new Exception(LanguageHelper::trans('notification.not_found'));
            }

            $readRow = $this->notificationReadModel->getFirst("
                SELECT *
                FROM notification_reads
                WHERE notification_id = $notificationId AND employee_id = $userId
            ");
            if ($readRow) {
                return $this->ajax();
            }

            $this->notificationReadModel->create([
                'notification_id' => $notificationId,
                'employee_id' => $userId,
                'read_at' => now(),
            ]);
            $this->ajax();
        } catch (Exception $e) {
            $this->ajax(['message' => $e->getMessage()], 500);
        }
    }
}
