<!--Carousel Wrapper-->
<?php
require_once 'views/layouts/default.php';
?>
<?php startblock('title')?>
<?= LanguageHelper::trans('employee.title') ?>
<?php endblock()?>

<?php startblock('content')?>
<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-header"><?= LanguageHelper::trans('employee.manage') ?></h5>
        <div class="card-header">
            <button class="btn btn-primary" onclick="openModal(1)"><?= LanguageHelper::trans('common.create') ?></button>
        </div>
    </div>
    <div class="card-body pt-0">
        <form class="row">
            <div class="form-group col-2">
                <label><?= LanguageHelper::trans('employee.fullname') ?></label>
                <input type="text" name="name" class="form-control" placeholder="<?= LanguageHelper::trans('employee.enter_fullname') ?>" value="<?= isset($_GET['name']) ? $_GET['name'] : '' ?>">
            </div>
            <div class="form-group col-2">
                <label><?= LanguageHelper::trans('common.email') ?></label>
                <input type="text" name="email" class="form-control" placeholder="<?= LanguageHelper::trans('employee.enter_email') ?>" value="<?= isset($_GET['email']) ? $_GET['email'] : '' ?>">
            </div>
            <div class="form-group col-2">
                <label><?= LanguageHelper::trans('employee.status') ?></label>
                <select name="status" class="form-control">
                    <option value=""><?= LanguageHelper::trans('common.select') ?></option>
                    <option value="1" <?=isset($_GET['status']) && $_GET['status'] == 1 ? 'selected' : ''?>><?= LanguageHelper::trans('employee.working') ?></option>
                    <option value="2" <?=isset($_GET['status']) && $_GET['status'] == 2 ? 'selected' : ''?>><?= LanguageHelper::trans('employee.resigned') ?></option>
                </select>
            </div>
            <div class="form-group col-2">
                <label><?= LanguageHelper::trans('department.title') ?></label>
                <select name="department_id" class="form-control">
                    <option value=""><?= LanguageHelper::trans('common.select') ?></option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?=$department['id']?>" <?=isset($_GET['department_id']) && $_GET['department_id'] == $department['id'] ? 'selected' : ''?>><?=$department['name']?></option>
                    <?php endforeach?>
                </select>
            </div>
            <div class="mt-3 col-3 d-flex align-items-end">
                <button class="btn btn-info" type="submit"><?= LanguageHelper::trans('common.filter') ?></button>
                <button type="button" class="btn btn-warning" style="margin-left: 5px;" onclick="window.location.href='<?=url('employee')?>'"><?= LanguageHelper::trans('common.clear_filter') ?></button>
            </div>
        </form>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead style="font-size: 10px">
                <tr>
                    <td><?= LanguageHelper::trans('department.title') ?></td>
                    <td><?= LanguageHelper::trans('employee.fullname') ?></td>
                    <td><?= LanguageHelper::trans('common.email') ?></td>
                    <td><?= LanguageHelper::trans('employee.role') ?></td>
                    <td><?= LanguageHelper::trans('employee.birthday') ?></td>
                    <td><?= LanguageHelper::trans('employee.address') ?></td>
                    <td><?= LanguageHelper::trans('employee.phone') ?></td>
                    <td><?= LanguageHelper::trans('employee.gender') ?></td>
                    <td><?= LanguageHelper::trans('employee.id_card') ?></td>
                    <td><?= LanguageHelper::trans('employee.position') ?></td>
                    <td><?= LanguageHelper::trans('employee.status') ?></td>
                    <td><?= LanguageHelper::trans('common.action') ?></td>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0" style="font-size: 13px">
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?=$employee['department_name'] ?? 'N/A'?></td>
                        <td><?=$employee['name'] ?? 'N/A'?></td>
                        <td><?=$employee['email']?></td>
                        <td><?= getRoleLabel($employee['role']) ?></td>
                        <td><?=$employee['birthday'] && $employee['birthday'] != '0000-00-00' ? dateFromStr($employee['birthday'], 'd/m/Y') : 'N/A'?></td>
                        <td><?=$employee['address'] ? $employee['address'] : 'N/A'?></td>
                        <td><?=$employee['phone_number'] ? $employee['phone_number'] : 'N/A'?></td>
                        <td><?=$employee['gender'] ? ($employee['gender'] == 'male' ? LanguageHelper::trans('common.male') : LanguageHelper::trans('common.female')) : 'N/A'?></td>
                        <td><?=$employee['cccd'] ? $employee['cccd'] : 'N/A'?></td>
                        <td><?=$employee['position'] ? $employee['position'] : 'N/A'?></td>
                        <td><?=$employee['status'] == 1 ? LanguageHelper::trans('employee.working') : LanguageHelper::trans('employee.resigned')?></td>
                        <td>
                            <?php $user = Auth::getUser('mvc_employee'); ?>
                            <?php if ($user['id'] != $employee['id'] && $employee['role'] != employee_role_types['admin']): ?>
                                <a href="javascript:void(0);"
                                    class="text-info ms-2"
                                    onclick='openModal(2, <?=json_encode($employee)?>)'
                                >
                                    <i class="bx bx-edit-alt me-1 text-info"></i> <?= LanguageHelper::trans('common.edit') ?>
                                </a>
                                <a
                                    href="javascript:void(0);"
                                    onclick='handleDelete(<?=json_encode($employee)?>)'
                                    class="btn btn-sm btn-danger"
                                >
                                    <i class="bx bx-trash me-1"></i> <?= LanguageHelper::trans('common.delete') ?>
                                </a>
                                <?php if (isRoleAdmin()): ?>
                                    <a
                                        href="javascript:void(0);"
                                        class="text-success ms-2"
                                        onclick='handleGenerateNewPassword(<?=json_encode($employee)?>)'
                                    >
                                        <i class="bx bx-key me-1 text-success"></i> <?= LanguageHelper::trans('common.reset_password') ?>
                                    </a>
                                <?php endif?>
                            <?php endif?>
                        </td>
                    </tr>
                <?php endforeach?>
            </tbody>
            <tfooter>
                <?php if (count($employees) > 0): ?>
                    <tr>
                        <td class="pt-4">
                            <?=$pagination?>
                        </td>
                    </tr>
                <?php else: ?>
                    <tr><td>Không có bản ghi</td></tr>
                <?php endif?>
            </tfooter>
        </table>
    </div>
</div>
<div class="modal fade" id="modal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form onsubmit="return handleSave()">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle"><?= LanguageHelper::trans('employee.create_update') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="employee_department_id" class="form-label"><?= LanguageHelper::trans('department.title') ?></label>
                            <select id="employee_department_id" style="width: 100%" class="form-control"></select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="employee_name" class="form-label"><?= LanguageHelper::trans('employee.fullname') ?></label>
                            <input type="text" id="employee_name" class="form-control" placeholder="<?= LanguageHelper::trans('employee.enter_fullname') ?>">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="employee_email" class="form-label"><?= LanguageHelper::trans('common.email') ?></label>
                            <input type="email" id="employee_email" class="form-control" placeholder="<?= LanguageHelper::trans('employee.enter_email') ?>" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="employee_birthday" class="form-label"><?= LanguageHelper::trans('employee.birthday') ?></label>
                            <input type="date" id="employee_birthday" class="form-control" placeholder="<?= LanguageHelper::trans('employee.enter_birthday') ?>">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="employee_cccd" class="form-label"><?= LanguageHelper::trans('employee.id_card') ?></label>
                            <input type="text" id="employee_cccd" class="form-control" placeholder="<?= LanguageHelper::trans('employee.enter_id_card') ?>">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="employee_address" class="form-label"><?= LanguageHelper::trans('employee.address') ?></label>
                            <input type="text" id="employee_address" class="form-control" placeholder="<?= LanguageHelper::trans('employee.enter_address') ?>">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="employee_phone_number" class="form-label"><?= LanguageHelper::trans('employee.phone') ?></label>
                            <input type="text" max="15" id="employee_phone_number" class="form-control" placeholder="<?= LanguageHelper::trans('employee.enter_phone') ?>">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="employee_position" class="form-label"><?= LanguageHelper::trans('employee.position') ?></label>
                            <input type="text" id="employee_position" class="form-control" placeholder="<?= LanguageHelper::trans('employee.enter_position') ?>">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="employee_gender" class="form-label"><?= LanguageHelper::trans('employee.gender') ?></label>
                            <select id="employee_gender" class="form-control" >
                                <option value=""><?= LanguageHelper::trans('common.select') ?></option>
                                <option value="male"><?= LanguageHelper::trans('common.male') ?></option>
                                <option value="female"><?= LanguageHelper::trans('common.female') ?></option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="employee_status" class="form-label"><?= LanguageHelper::trans('employee.status') ?></label>
                            <select id="employee_status" class="form-control" required>
                                <option value=""><?= LanguageHelper::trans('common.select') ?></option>
                                <option value="1"><?= LanguageHelper::trans('employee.working') ?></option>
                                <option value="2"><?= LanguageHelper::trans('employee.resigned') ?></option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="employee_role" class="form-label"><?= LanguageHelper::trans('employee.role') ?></label>
                            <select id="employee_role" class="form-control" required>
                                <option value=""><?= LanguageHelper::trans('common.select') ?></option>
                                <?php foreach(employee_roles as $role): ?>
                                    <option value="<?= $role['value'] ?>"><?= LanguageHelper::trans('employee_role.' . $role['label']) ?></option>
                                <?php endforeach ?>
                            </select>
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
<div class="modal fade" id="modal-password" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle"><?= LanguageHelper::trans('common.password') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= LanguageHelper::trans('common.password') ?>: <span class="password"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= LanguageHelper::trans('common.exit') ?>
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    // 1 create
    // 2 update
    var action = 1
    var dataModel = {
        id: null,
        department_id: null,
        name: null,
        email: null,
        password: null,
        role: null,
        birthday: null,
        address: null,
        phone_number: null,
        gender: null,
        cccd: null,
        position: null,
        status: null,
    }

    function handleGenerateNewPassword(data) {
        let password = prompt(`Nhập mật khẩu mới cho nhân viên: ${data.name}`)
        if (password) {
            $.ajax({
                url: '<?=url('employee')?>/generatePassword',
                type: "post",
                data: {
                    id: data.id,
                    password: password
                },
                dataType: "application/json",
                success: (data) => {},
                error: (error) => {
                    let response = $.parseJSON(error.responseText)
                    alert(response.data.message)
                    if (response.status == 1) window.location.reload()
                }
            });
        }
    }

    function handleDelete(data) {
        let cf = confirm(`Bạn muốn xoá nhân viên: ${data.name}`)
        if (cf) {
            $.ajax({
                url: `<?=url('employee')?>/delete/${data.id}`,
                dataType: "application/json",
                success: (data) => {},
                error: (error) => {
                    window.location.reload()
                }
            });
        }
    }
    function openModal(type, data = null) {
        if (type == 1) {
            document.querySelector('#modal form') ? document.querySelector('#modal form').reset() : true
            $(`#employee_email`).prop('disabled', false)
        }  else {
            dataModel = data
            for (let i in dataModel) {
                if (i == 'email') {
                    $(`#employee_${i}`).prop('disabled', true)
                }
                if (i == 'department_id') {
                    setupSelect2({
                        target: '#employee_department_id',
                        searchUrl: '<?= url('department/search') ?>',
                        selectedItem: {
                            id: dataModel[i],
                            text: dataModel['department_name']
                        },
                        processResults: function (data) {
                            return {
                                results: data.data.map(item => ({
                                    id: item.id,
                                    text: item.name,
                                }))
                            };
                        }
                    });
                }
                $(`#employee_${i}`).val(dataModel[i])
            }
        }
        action = type
        $('#modal').modal('show')
    }

    function handleSave() {
        if (action == 1) {
            for (let i in dataModel) {
                if ($(`#employee_${i}`).length) {
                    dataModel[i] = $(`#employee_${i}`).val()
                }
            }
            delete dataModel.id
            $.ajax({
                url: `<?=url('employee')?>/create`,
                type: "post",
                data: dataModel,
                dataType: "application/json",
                success: (data) => {},
                error: (error) => {
                    window.location.reload()
                }
            });
        } else {
            for (let i in dataModel) {
                if ($(`#employee_${i}`).length) {
                    dataModel[i] = $(`#employee_${i}`).val()
                }
            }
            dataModel.updated_at = new Date().toISOString().slice(0, 19).replace('T', ' ');
            delete dataModel.created_at
            delete dataModel.deleted_at
            $.ajax({
                url: `<?=url('employee')?>/update`,
                type: "post",
                data: dataModel,
                dataType: "application/json",
                success: (data) => {},
                error: (error) => {
                    window.location.reload()
                }
            });
        }
        return false
    }
</script>
<?php endblock()?>