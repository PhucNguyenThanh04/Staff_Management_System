<?php
require_once 'views/layouts/default.php';
?>
<?php startblock('title')?>
<?= LanguageHelper::trans('attendance.title') ?>
<?php endblock()?>

<?php startblock('content')?>
<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-header"><?= LanguageHelper::trans('attendance.manage') ?></h5>
        <?php if (isRoleAdmin() || isRoleKetoan()): ?>
        <div class="card-header">
            <button class="btn btn-primary" onclick="openModal(1)"><?= LanguageHelper::trans('common.create') ?></button>
        </div>
        <?php endif ?>
    </div>
    <div class="card-body pt-0">
        <form class="row">
            <div class="form-group col-2">
                <label><?= LanguageHelper::trans('employee.title') ?></label>
                <select id="search_employee_id" name="employee_id" style="width: 100%" value="<?= isset($_GET['employee_id']) ? $_GET['employee_id'] : '' ?>"></select>
            </div>
            <div class="form-group col-2">
                <label>Theo ngày</label>
                <input type="date" name="attendance_date" id="date-input" class="form-control" value="<?= isset($_GET['attendance_date']) ? $_GET['attendance_date'] : '' ?>"/>
            </div>
            <div class="form-group col-4">
                <label for="month-input">Theo tháng</label>
                <input id="month-input" name="by_month" type="month" class="form-control" value="<?= isset($_GET['by_month']) ? $_GET['by_month'] : '' ?>" />
            </div>
            <div class="mt-3 col-3 d-flex align-items-end">
                <button class="btn btn-info" type="submit"><?= LanguageHelper::trans('common.filter') ?></button>
                <button type="button" class="btn btn-warning" style="margin-left: 5px;" onclick="window.location.href='<?=url('attendance')?>'"><?= LanguageHelper::trans('common.clear_filter') ?></button>
            </div>
        </form>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th><?= LanguageHelper::trans('employee.title') ?></th>
                    <th><?= LanguageHelper::trans('attendance.date') ?></th>
                    <th><?= LanguageHelper::trans('attendance.check_in') ?></th>
                    <th><?= LanguageHelper::trans('attendance.check_out') ?></th>
                    <th><?= LanguageHelper::trans('attendance.working_hours') ?></th>
                    <th><?= LanguageHelper::trans('attendance.work_point') ?></th>
                    <th><?= LanguageHelper::trans('attendance.note') ?></th>
                    <th><?= LanguageHelper::trans('common.action') ?></th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php foreach ($attendances as $attendance): ?>
                    <tr>
                        <td><?=$attendance['employee_name']?> (<?=$attendance['employee_email']?>)</td>
                        <td><?=dateFromStr($attendance['attendance_date'], 'd/m/Y')?></td>
                        <td><?=$attendance['check_in']?></td>
                        <td><?=$attendance['check_out']?></td>
                        <td><?=$attendance['working_hours']?></td>
                        <td><?=$attendance['work_point']?></td>
                        <td><?=$attendance['note']?></td>
                        <td>
                            <?php if (isRoleAdmin() || isRoleKetoan() || isRoleNhansu()): ?>
                            <a href="javascript:void(0);" class="text-info ms-2" onclick='openModal(2, <?=json_encode($attendance)?>)'><i class="bx bx-edit-alt me-1 text-info"></i> <?= LanguageHelper::trans('common.edit') ?></a>
                            <a href="javascript:void(0);" onclick='handleDelete(<?=json_encode($attendance)?>)' class="text-danger ms-2"><i class="bx bx-trash me-1 text-danger"></i> <?= LanguageHelper::trans('common.delete') ?></a>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form onsubmit="return handleSave()">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle"><?= LanguageHelper::trans('attendance.create_update') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="attendance_employee_id" class="form-label"><?= LanguageHelper::trans('employee.title') ?></label>
                            <select id="attendance_employee_id" class="form-control" required style="width: 100%;"></select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="attendance_attendance_date" class="form-label"><?= LanguageHelper::trans('attendance.date') ?></label>
                            <input type="date" id="attendance_attendance_date" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="attendance_check_in" class="form-label"><?= LanguageHelper::trans('attendance.check_in') ?></label>
                            <input type="time" id="attendance_check_in" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="attendance_check_out" class="form-label"><?= LanguageHelper::trans('attendance.check_out') ?></label>
                            <input type="time" id="attendance_check_out" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="attendance_note" class="form-label"><?= LanguageHelper::trans('attendance.note') ?></label>
                            <input type="text" id="attendance_note" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <?= LanguageHelper::trans('common.exit') ?>
                    </button>
                    <button type="submit" class="btn btn-primary"><?= LanguageHelper::trans('common.save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var action = 1
    var dataModel = {
        id: null,
        employee_id: null,
        attendance_date: null,
        check_in: null,
        check_out: null,
        note: ''
    }
    document.addEventListener("DOMContentLoaded", function () {
        const urlParams = new URLSearchParams(window.location.search)
        console.log(urlParams.get('employee_id'));
        
        setupSelect2({
            target: '#search_employee_id',
            searchUrl: '<?= url('employee/search') ?>',
            selectedItem: {
                'id': '<?= isset($employee) ? $employee['id'] : '' ?>',
                'text': '<?= isset($employee) ? "{$employee['name']} ({$employee['email']})" : '' ?>',
            },
            processResults: function (data) {
                return {
                    results: data.data.map(item => ({
                        id: item.id,
                        text: `${item.name || ''} (${item.email})`,
                    }))
                };
            }
        });
    });

    function handleDelete(data) {
        if (confirm(`<?= LanguageHelper::trans('attendance.confirm_delete') ?>`)) {
            $.ajax({
                url: `<?=url('attendance')?>/delete/${data.id}`,
                success: () => window.location.reload(),
                error: () => window.location.reload()
            });
        }
    }

    function openModal(type, data = null) {
        if (type === 1) {
            document.querySelector('#modal form')?.reset()
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('attendance_attendance_date').value = today;
            setupSelect2({
                target: '#attendance_employee_id',
                searchUrl: '<?= url('employee/search') ?>',
                processResults: function (data) {
                    return {
                        results: data.data.map(item => ({
                            id: item.id,
                            text: `${item.name || ''} (${item.email})`,
                        }))
                    };
                }
            });
        } else {
            dataModel = data
            for (let key in dataModel) {
                if (key === 'employee_id') {
                    $(`#attendance_employee_id`).html(`<option value="${dataModel.employee_id}">${dataModel.employee_name || ''} (${dataModel.employee_email})</option>`).select2({ disabled: true });
                } else {
                    $(`#attendance_${key}`).val(dataModel[key])
                }
            }
        }
        action = type
        $('#modal').modal('show')
    }

    function handleSave() {
        for (let key in dataModel) {
            if ($(`#attendance_${key}`).length) {
                dataModel[key] = $(`#attendance_${key}`).val()
            }
        }

        const url = action === 1 ? `<?=url('attendance')?>/create` : `<?=url('attendance')?>/update`
        $.ajax({
            url,
            type: "post",
            data: dataModel,
            success: () => {
                window.location.reload()
            },
            error: (err) => {
                console.log(err);
                window.location.reload()
            }
        });
        return false
    }

    const dateInput = document.getElementById('date-input');
    const monthInput = document.getElementById('month-input');
    let isChanging = false; // Ngăn vòng lặp vô hạn khi thay đổi lẫn nhau

    dateInput.addEventListener('input', () => {
        if (isChanging) return;
        isChanging = true;
        monthInput.value = '';
        isChanging = false;
    });

    monthInput.addEventListener('input', () => {
        if (isChanging) return;
        isChanging = true;
        dateInput.value = '';
        isChanging = false;
    });
</script>
<?php endblock()?>
