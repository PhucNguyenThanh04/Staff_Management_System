<?php
require_once 'views/layouts/default.php';

function getNotificationTargetTypeLabel($type)
{
    $labels = [
        1 => LanguageHelper::trans('notification.target_all'),
        2 => LanguageHelper::trans('notification.target_role'),
        3 => LanguageHelper::trans('notification.target_department'),
        4 => LanguageHelper::trans('notification.target_employee'),
    ];
    return $labels[$type] ?? '';
}

function getNotificationRoleLabel($value)
{
    if ($value === null || $value === '') {
        return '';
    }
    return LanguageHelper::trans('employee_role.' . getRoleLabel(intval($value)));
}
?>
<?php startblock('title')?><?= LanguageHelper::trans('notification.title') ?><?php endblock()?>

<?php startblock('content')?>
<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-header"><?= LanguageHelper::trans('notification.manage') ?></h5>
        <?php if ($can_manage): ?>
        <div class="card-header">
            <button class="btn btn-primary" onclick="openModal(1)"><?= LanguageHelper::trans('common.create') ?></button>
        </div>
        <?php endif ?>
    </div>
    <div class="card-body pt-0">
        <form class="row">
            <div class="form-group col-4">
                <label><?= LanguageHelper::trans('notification.search') ?></label>
                <input type="text" class="form-control" name="q" value="<?= $_GET['q'] ?? '' ?>" placeholder="<?= LanguageHelper::trans('notification.search_placeholder') ?>">
            </div>
            <div class="form-group col-2">
                <label><?= LanguageHelper::trans('notification.target') ?></label>
                <select class="form-control" name="target_type">
                    <option value=""><?= LanguageHelper::trans('common.select') ?></option>
                    <option value="1" <?= isset($_GET['target_type']) && $_GET['target_type'] == 1 ? 'selected' : '' ?>><?= LanguageHelper::trans('notification.target_all') ?></option>
                    <option value="2" <?= isset($_GET['target_type']) && $_GET['target_type'] == 2 ? 'selected' : '' ?>><?= LanguageHelper::trans('notification.target_role') ?></option>
                    <option value="3" <?= isset($_GET['target_type']) && $_GET['target_type'] == 3 ? 'selected' : '' ?>><?= LanguageHelper::trans('notification.target_department') ?></option>
                    <option value="4" <?= isset($_GET['target_type']) && $_GET['target_type'] == 4 ? 'selected' : '' ?>><?= LanguageHelper::trans('notification.target_employee') ?></option>
                </select>
            </div>
            <?php if ($can_manage): ?>
            <div class="form-group col-2">
                <label><?= LanguageHelper::trans('notification.status') ?></label>
                <select class="form-control" name="is_active">
                    <option value=""><?= LanguageHelper::trans('common.select') ?></option>
                    <option value="1" <?= isset($_GET['is_active']) && $_GET['is_active'] === '1' ? 'selected' : '' ?>><?= LanguageHelper::trans('notification.active') ?></option>
                    <option value="0" <?= isset($_GET['is_active']) && $_GET['is_active'] === '0' ? 'selected' : '' ?>><?= LanguageHelper::trans('notification.inactive') ?></option>
                </select>
            </div>
            <?php endif ?>
            <div class="form-group col-2">
                <label><?= LanguageHelper::trans('notification.read_status') ?></label>
                <select class="form-control" name="only_unread">
                    <option value="0"><?= LanguageHelper::trans('notification.all_status') ?></option>
                    <option value="1" <?= isset($_GET['only_unread']) && $_GET['only_unread'] == 1 ? 'selected' : '' ?>><?= LanguageHelper::trans('notification.only_unread') ?></option>
                </select>
            </div>
            <div class="col-2 d-flex align-items-end">
                <button class="btn btn-info" type="submit"><?= LanguageHelper::trans('common.filter') ?></button>
                <a href="<?=url('notification')?>" class="btn btn-warning ms-2"><?= LanguageHelper::trans('common.clear_filter') ?></a>
            </div>
        </form>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th><?= LanguageHelper::trans('notification.title_label') ?></th>
                    <th><?= LanguageHelper::trans('notification.target') ?></th>
                    <th><?= LanguageHelper::trans('notification.sender') ?></th>
                    <th><?= LanguageHelper::trans('notification.read_status') ?></th>
                    <th><?= LanguageHelper::trans('notification.created_at') ?></th>
                    <?php if ($can_manage): ?>
                    <th><?= LanguageHelper::trans('notification.total_readers') ?></th>
                    <?php endif ?>
                    <th><?= LanguageHelper::trans('common.action') ?></th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php foreach ($notifications as $notification): ?>
                <?php
                    $targetLabel = '';
                    if ($notification['target_type'] == 1) {
                        $targetLabel = LanguageHelper::trans('notification.target_all');
                    } else if ($notification['target_type'] == 2) {
                        $targetLabel = getNotificationRoleLabel($notification['target_value']);
                    } else if ($notification['target_type'] == 3) {
                        $targetLabel = $notification['target_department_name'] ?? '';
                    } else if ($notification['target_type'] == 4) {
                        $targetLabel = $notification['target_employee_name'] ?? '';
                    }
                ?>
                <tr class="<?= $notification['read_at'] ? '' : 'table-warning' ?>">
                    <td>
                        <div class="fw-bold">
                            <?= htmlspecialchars($notification['title']) ?>
                            <?php if ($notification['is_pinned'] == 1): ?>
                            <span class="badge bg-danger"><?= LanguageHelper::trans('notification.pinned') ?></span>
                            <?php endif ?>
                            <?php if ($notification['is_active'] != 1): ?>
                            <span class="badge bg-secondary"><?= LanguageHelper::trans('notification.inactive') ?></span>
                            <?php endif ?>
                        </div>
                        <small class="text-muted"><?= htmlspecialchars(substr($notification['content'], 0, 80)) ?><?= strlen($notification['content']) > 80 ? '...' : '' ?></small>
                    </td>
                    <td>
                        <div><?= getNotificationTargetTypeLabel(intval($notification['target_type'])) ?></div>
                        <small class="text-muted"><?= htmlspecialchars($targetLabel) ?></small>
                    </td>
                    <td><?= htmlspecialchars($notification['sender_name'] ?? 'N/A') ?></td>
                    <td>
                        <?php if ($notification['read_at']): ?>
                            <span class="badge bg-success"><?= LanguageHelper::trans('notification.read') ?></span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark"><?= LanguageHelper::trans('notification.unread') ?></span>
                        <?php endif ?>
                    </td>
                    <td><?= $notification['created_at'] ? dateFromStr($notification['created_at'], 'd/m/Y H:i') : '' ?></td>
                    <?php if ($can_manage): ?>
                    <td><?= intval($notification['read_count']) ?></td>
                    <?php endif ?>
                    <td>
                        <a href="javascript:void(0);" class="text-primary" onclick='openViewModal(<?=json_encode($notification)?>, <?=json_encode($targetLabel)?>)'>
                            <i class="bx bx-show-alt me-1"></i> <?= LanguageHelper::trans('notification.view') ?>
                        </a>
                        <?php if (!$notification['read_at']): ?>
                        <a href="javascript:void(0);" class="text-success ms-2" onclick="markRead(<?=$notification['id']?>)">
                            <i class="bx bx-check-circle me-1"></i> <?= LanguageHelper::trans('notification.mark_read') ?>
                        </a>
                        <?php endif ?>
                        <?php if ($can_manage): ?>
                        <a href="javascript:void(0);" class="text-info ms-2" onclick='openModal(2, <?=json_encode($notification)?>)'>
                            <i class="bx bx-edit-alt me-1"></i> <?= LanguageHelper::trans('common.edit') ?>
                        </a>
                        <a href="javascript:void(0);" class="text-danger ms-2" onclick='handleDelete(<?=json_encode($notification)?>)'>
                            <i class="bx bx-trash me-1"></i> <?= LanguageHelper::trans('common.delete') ?>
                        </a>
                        <?php endif ?>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <div class="card-body">
        <?= $pagination ?? '' ?>
    </div>
</div>

<?php if ($can_manage): ?>
<div class="modal fade" id="modal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form onsubmit="return handleSave()">
                <div class="modal-header">
                    <h5 class="modal-title"><?= LanguageHelper::trans('notification.create_update') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="notification_title" class="form-label"><?= LanguageHelper::trans('notification.title_label') ?></label>
                            <input type="text" id="notification_title" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="notification_content" class="form-label"><?= LanguageHelper::trans('notification.content') ?></label>
                            <textarea id="notification_content" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="notification_target_type" class="form-label"><?= LanguageHelper::trans('notification.target') ?></label>
                            <select id="notification_target_type" class="form-control" onchange="handleChangeTargetType(this.value)">
                                <option value="1"><?= LanguageHelper::trans('notification.target_all') ?></option>
                                <option value="2"><?= LanguageHelper::trans('notification.target_role') ?></option>
                                <option value="3"><?= LanguageHelper::trans('notification.target_department') ?></option>
                                <option value="4"><?= LanguageHelper::trans('notification.target_employee') ?></option>
                            </select>
                        </div>
                        <div class="col-12 mb-3 d-none" id="target-role-wrapper">
                            <label for="notification_target_role" class="form-label"><?= LanguageHelper::trans('notification.role_value') ?></label>
                            <select id="notification_target_role" class="form-control">
                                <option value=""><?= LanguageHelper::trans('common.select') ?></option>
                                <?php foreach (employee_roles as $role): ?>
                                    <option value="<?=$role['value']?>"><?= LanguageHelper::trans('employee_role.' . $role['label']) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-12 mb-3 d-none" id="target-department-wrapper">
                            <label for="notification_target_department" class="form-label"><?= LanguageHelper::trans('notification.department_value') ?></label>
                            <select id="notification_target_department" class="form-control" style="width: 100%"></select>
                        </div>
                        <div class="col-12 mb-3 d-none" id="target-employee-wrapper">
                            <label for="notification_target_employee" class="form-label"><?= LanguageHelper::trans('notification.employee_value') ?></label>
                            <select id="notification_target_employee" class="form-control" style="width: 100%"></select>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="notification_is_pinned" class="form-label"><?= LanguageHelper::trans('notification.pinned') ?></label>
                            <select id="notification_is_pinned" class="form-control">
                                <option value="0"><?= LanguageHelper::trans('notification.not_pinned') ?></option>
                                <option value="1"><?= LanguageHelper::trans('notification.pinned') ?></option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="notification_is_active" class="form-label"><?= LanguageHelper::trans('notification.status') ?></label>
                            <select id="notification_is_active" class="form-control">
                                <option value="1"><?= LanguageHelper::trans('notification.active') ?></option>
                                <option value="0"><?= LanguageHelper::trans('notification.inactive') ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= LanguageHelper::trans('common.exit') ?></button>
                    <button type="submit" class="btn btn-primary"><?= LanguageHelper::trans('common.save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif ?>

<div class="modal fade" id="view-modal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= LanguageHelper::trans('notification.view') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5 id="view-title"></h5>
                <p class="text-muted mb-2"><span id="view-meta"></span></p>
                <div id="view-content" style="white-space: pre-line"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= LanguageHelper::trans('common.exit') ?></button>
            </div>
        </div>
    </div>
</div>

<script>
var canManage = <?= $can_manage ? 'true' : 'false' ?>;
var action = 1;
var dataModel = {
    id: null,
    title: '',
    content: '',
    target_type: 1,
    target_value: '',
    is_pinned: 0,
    is_active: 1
};

function openViewModal(data, targetLabel) {
    $('#view-title').text(data.title || '');
    $('#view-meta').text(`${data.sender_name || 'N/A'} • ${targetLabel || ''}`);
    $('#view-content').text(data.content || '');
    $('#view-modal').modal('show');
    if (!data.read_at) {
        markRead(data.id, false);
    }
}

function markRead(notificationId, withReload = true) {
    $.ajax({
        url: `<?=url('notification')?>/markRead`,
        type: "post",
        data: { notification_id: notificationId },
        success: () => {
            if (withReload) window.location.reload();
        },
        error: () => {
            if (withReload) window.location.reload();
        }
    });
}

if (canManage) {
    function initTargetSelect2() {
        setupSelect2({
            target: '#notification_target_department',
            searchUrl: '<?= url('department/search') ?>',
            processResults: function (data) {
                return {
                    results: data.data.map(item => ({
                        id: item.id,
                        text: item.name
                    }))
                };
            }
        });
        setupSelect2({
            target: '#notification_target_employee',
            searchUrl: '<?= url('employee/search') ?>',
            processResults: function (data) {
                return {
                    results: data.data.map(item => ({
                        id: item.id,
                        text: `${item.name || ''} (${item.email || ''})`
                    }))
                };
            }
        });
    }

    function resetTargetInputs() {
        $('#notification_target_role').val('');
        $('#notification_target_department').val(null).trigger('change');
        $('#notification_target_employee').val(null).trigger('change');
    }

    function handleChangeTargetType(type) {
        $('#target-role-wrapper').addClass('d-none');
        $('#target-department-wrapper').addClass('d-none');
        $('#target-employee-wrapper').addClass('d-none');
        if (parseInt(type) === 2) {
            $('#target-role-wrapper').removeClass('d-none');
        }
        if (parseInt(type) === 3) {
            $('#target-department-wrapper').removeClass('d-none');
        }
        if (parseInt(type) === 4) {
            $('#target-employee-wrapper').removeClass('d-none');
        }
    }

    function openModal(type, data = null) {
        if (type === 1) {
            document.querySelector('#modal form')?.reset();
            dataModel = {
                id: null,
                title: '',
                content: '',
                target_type: 1,
                target_value: '',
                is_pinned: 0,
                is_active: 1
            };
            resetTargetInputs();
            handleChangeTargetType(1);
            $('#notification_target_type').val('1');
        } else {
            dataModel = data;
            $('#notification_title').val(dataModel.title || '');
            $('#notification_content').val(dataModel.content || '');
            $('#notification_target_type').val(dataModel.target_type || 1);
            $('#notification_is_pinned').val(dataModel.is_pinned || 0);
            $('#notification_is_active').val(dataModel.is_active || 1);
            resetTargetInputs();
            handleChangeTargetType(dataModel.target_type || 1);
            if (parseInt(dataModel.target_type) === 2) {
                $('#notification_target_role').val(dataModel.target_value || '');
            }
            if (parseInt(dataModel.target_type) === 3) {
                const label = dataModel.target_department_name || '';
                if (dataModel.target_value && label) {
                    $('#notification_target_department').html(`<option value="${dataModel.target_value}" selected>${label}</option>`).trigger('change');
                }
            }
            if (parseInt(dataModel.target_type) === 4) {
                const label = dataModel.target_employee_name || '';
                if (dataModel.target_value && label) {
                    $('#notification_target_employee').html(`<option value="${dataModel.target_value}" selected>${label}</option>`).trigger('change');
                }
            }
        }
        action = type;
        $('#modal').modal('show');
    }

    function handleDelete(data) {
        if (confirm(`<?= LanguageHelper::trans('notification.confirm_delete') ?>: ${data.title}`)) {
            $.ajax({
                url: `<?=url('notification')?>/delete/${data.id}`,
                success: () => window.location.reload(),
                error: () => window.location.reload()
            });
        }
    }

    function handleSave() {
        dataModel.title = $('#notification_title').val();
        dataModel.content = $('#notification_content').val();
        dataModel.target_type = $('#notification_target_type').val();
        dataModel.is_pinned = $('#notification_is_pinned').val();
        dataModel.is_active = $('#notification_is_active').val();

        if (parseInt(dataModel.target_type) === 1) {
            dataModel.target_value = '';
        } else if (parseInt(dataModel.target_type) === 2) {
            dataModel.target_value = $('#notification_target_role').val();
        } else if (parseInt(dataModel.target_type) === 3) {
            dataModel.target_value = $('#notification_target_department').val();
        } else if (parseInt(dataModel.target_type) === 4) {
            dataModel.target_value = $('#notification_target_employee').val();
        }

        if (action === 1) {
            delete dataModel.id;
            $.ajax({
                url: `<?=url('notification')?>/create`,
                type: "post",
                data: dataModel,
                success: () => window.location.reload(),
                error: () => window.location.reload()
            });
        } else {
            $.ajax({
                url: `<?=url('notification')?>/update`,
                type: "post",
                data: dataModel,
                success: () => window.location.reload(),
                error: () => window.location.reload()
            });
        }
        return false;
    }

    document.addEventListener("DOMContentLoaded", function () {
        initTargetSelect2();
        handleChangeTargetType(1);
    });
}
</script>
<?php endblock()?>
