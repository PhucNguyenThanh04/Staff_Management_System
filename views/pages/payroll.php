<?php
require_once 'views/layouts/default.php';
?>
<?php startblock('title')?>
<?= LanguageHelper::trans('payroll.title') ?>
<?php endblock()?>

<?php startblock('content')?>
<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-header"><?= LanguageHelper::trans('payroll.manage') ?></h5>
        <?php if (isRoleAdmin() || isRoleKetoan()): ?>
        <div class="card-header">
            <button class="btn btn-primary" onclick="openModal(1)"><?= LanguageHelper::trans('common.create') ?></button>
        </div>
        <?php endif ?>
    </div>
    <?php if (isRoleAdmin() || isRoleKetoan()): ?>
        <div class="card-header pt-0">
            <form>
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="statistic-input">Thống kê lương theo tháng</label>
                            <input onchange="this.form.submit()" name="statistic_by_time" type="month" id="statistic-input" class="form-control" value="<?= isset($_GET['statistic_by_time']) ? $_GET['statistic_by_time'] : '' ?>" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php if (!isset($_GET['statistic_by_time'])): ?>
        <div class="card-header pt-0">
            <form>
                <label for="">Lọc</label>
                <input style="width: 300px" type="text" name="search" class="form-control" placeholder="<?= LanguageHelper::trans('common.search_employee') ?>" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>" />
            </form>
        </div>
        <?php endif ?>
    <?php endif ?>
    <?php if (isset($statistics)): ?>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th><?= LanguageHelper::trans('employee.title') ?></th>
                        <th><?= LanguageHelper::trans('payroll.net_salary') ?></th>
                        <th><?= LanguageHelper::trans('payroll.payroll_month') ?></th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <?php foreach ($statistics as $statistic): ?>
                        <tr>
                            <td><?=$statistic['employee_name']?> (<?=$statistic['employee_email']?>)</td>
                            <td><?=number_format($statistic['salary'])?> đ</td>
                            <td><?=$statistic['payroll_month'] ? dateFromStr($statistic['updated_at'], 'm/Y') : ''?></td>
                        </tr>
                    <?php endforeach?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th><?= LanguageHelper::trans('employee.title') ?></th>
                        <th><?= LanguageHelper::trans('payroll.base_salary') ?></th>
                        <th><?= LanguageHelper::trans('common.update_date') ?></th>
                        <th><?= LanguageHelper::trans('common.action') ?></th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <?php foreach ($payrolls as $payroll): ?>
                        <tr>
                            <td><?=$payroll['employee_name']?> (<?=$payroll['employee_email']?>)</td>
                            <td><?=number_format($payroll['base_salary'])?> đ</td>
                            <td><?=$payroll['updated_at'] ? dateFromStr($payroll['updated_at'], 'd/m/Y') : ''?></td>
                            <td>
                                <a href="<?= url('payroll-detail', ['payroll_id' => $payroll['id']]) ?>" class="text-success"><i class="bx bx-detail me-1 text-success"></i><?= LanguageHelper::trans('common.detail') ?></a>
                                <?php if (isRoleAdmin() || isRoleKetoan()): ?>
                                <a href="javascript:void(0);" class="text-info ms-4" onclick='openModal(2, <?=json_encode($payroll)?>)'><i class="bx bx-edit-alt me-1 text-info"></i> <?= LanguageHelper::trans('common.edit') ?></a>
                                <a href="javascript:void(0);" onclick='handleDelete(<?=json_encode($payroll)?>)' class="text-danger ms-4"><i class="bx bx-trash me-1 text-danger"></i> <?= LanguageHelper::trans('common.delete') ?></a>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endforeach?>
                </tbody>
            </table>
        </div>
    <?php endif ?>
</div>
<div class="modal fade" id="modal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form onsubmit="return handleSave()">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle"><?= LanguageHelper::trans('payroll.create_update') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="payroll_employee_id" class="form-label"><?= LanguageHelper::trans('employee.title') ?></label>
                            <select id="payroll_employee_id" class="form-control" required style="width: 100%;"></select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="payroll_base_salary" class="form-label"><?= LanguageHelper::trans('payroll.base_salary') ?></label>
                            <input type="number" id="payroll_base_salary" class="form-control" placeholder="<?= LanguageHelper::trans('payroll.enter_base_salary') ?>" required>
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
    // 1 create
    // 2 update
    var action = 1
    var dataModel = {
        id: null,
        employee_id: null,
        base_salary: null,
    }

    function handleDelete(data) {
        let cf = confirm(`<?= LanguageHelper::trans('payroll.confirm_delete') ?>: ${data.name}`)
        if (cf) {
            $.ajax({
                url: `<?=url('payroll')?>/delete/${data.id}`,
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
            setupSelect2({
                target: '#payroll_employee_id',
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
        }  else {
            dataModel = data
            for (let i in dataModel) {
                if (i == 'employee_id') {
                    $(`#payroll_${i}`).html('')
                    $(`#payroll_${i}`).append(`
                        <option value="${dataModel.employee_id}">${dataModel.employee_name || ''} (${dataModel.employee_email})</option>
                    `)
                    $(`#payroll_${i}`).select2({
                        disabled: true
                    });
                } else {
                    $(`#payroll_${i}`).val(dataModel[i])
                }
            }
        }
        action = type
        $('#modal').modal('show')
    }

    function handleSave() {
        if (action == 1) {
            for (let i in dataModel) {
                if ($(`#payroll_${i}`).length) {
                    dataModel[i] = $(`#payroll_${i}`).val()
                }
            }
            delete dataModel.id
            $.ajax({
                url: `<?=url('payroll')?>/create`,
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
                if ($(`#payroll_${i}`).length) {
                    dataModel[i] = $(`#payroll_${i}`).val()
                }
            }
            $.ajax({
                url: `<?=url('payroll')?>/update`,
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