<?php
require_once 'views/layouts/default.php';
?>
<?php startblock('title')?>
<?= LanguageHelper::trans('payroll.detail_title') ?>
<?php endblock()?>

<?php startblock('content')?>
<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-header"><?= LanguageHelper::trans('payroll.title_detail') ?>: <?=$employee['name']?> (<?=$employee['email']?>)</h5>
        <div class="card-header">
            <?php if (isRoleAdmin() || isRoleKetoan()): ?>
                <button class="btn btn-primary" onclick="openModal(1)"><?= LanguageHelper::trans('common.create') ?></button>
            <?php endif ?>
            <a href="<?= url('payroll') ?>" class="btn btn-secondary"><?= LanguageHelper::trans('common.back') ?></a>
        </div>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th><?= LanguageHelper::trans('payroll.base_salary') ?></th>
                    <th><?= LanguageHelper::trans('payroll.bonus') ?></th>
                    <th><?= LanguageHelper::trans('payroll.deductions') ?></th>
                    <th><?= LanguageHelper::trans('payroll.insurance') ?></th>
                    <th><?= LanguageHelper::trans('payroll.net_salary') ?></th>
                    <th><?= LanguageHelper::trans('payroll.payroll_month') ?></th>
                    <th><?= LanguageHelper::trans('payroll.payment_date') ?></th>
                    <th><?= LanguageHelper::trans('common.update_date') ?></th>
                    <?php if (isRoleAdmin() || isRoleKetoan()): ?>
                    <th><?= LanguageHelper::trans('common.action') ?></th>
                    <?php endif ?>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php foreach ($payroll_details as $payroll_detail): ?>
                    <tr>
                        <td><?=number_format($payroll_detail['base_salary'])?> đ</td>
                        <td><?=number_format($payroll_detail['bonus'])?> đ</td>
                        <td><?=number_format($payroll_detail['deductions'])?> đ</td>
                        <td><?=number_format($payroll_detail['insurance'])?> đ</td>
                        <td><?=number_format($payroll_detail['net_salary'])?> đ</td>
                        <td><?=$payroll_detail['payroll_month'] ? dateFromStr($payroll_detail['payroll_month'], 'm/Y') : ''?></td>
                        <td><?=$payroll_detail['payment_date'] ? dateFromStr($payroll_detail['payment_date'], 'd/m/Y') : ''?></td>
                        <td><?=$payroll_detail['updated_at'] ? dateFromStr($payroll_detail['updated_at'], 'd/m/Y') : ''?></td>
                        <?php if (isRoleAdmin() || isRoleKetoan()): ?>
                        <td>
                            <a href="javascript:void(0);" class="text-info ms-4" onclick='openModal(2, <?=json_encode($payroll_detail)?>)'><i class="bx bx-edit-alt me-1 text-info"></i> <?= LanguageHelper::trans('common.edit') ?></a>
                            <a href="javascript:void(0);" onclick='handleDelete(<?=json_encode($payroll_detail)?>)' class="text-danger ms-4"><i class="bx bx-trash me-1 text-danger"></i> <?= LanguageHelper::trans('common.delete') ?></a>
                        </td>
                        <?php endif ?>
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
                    <h5 class="modal-title" id="modalCenterTitle"><?= LanguageHelper::trans('payroll.detail_create_update') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="payroll_detail_payroll_month" class="form-label">Tháng lương</label>
                            <input type="month" id="payroll_detail_payroll_month" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="payroll_detail_base_salary" class="form-label"><?= LanguageHelper::trans('payroll.base_salary') ?></label>
                            <input type="number" id="payroll_detail_base_salary" class="form-control" placeholder="<?= LanguageHelper::trans('payroll.enter_base_salary') ?>" required>
                        </div>
                        <div id="div-salary">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="payroll_detail_bonus" class="form-label"><?= LanguageHelper::trans('payroll.bonus') ?></label>
                            <input type="number" id="payroll_detail_bonus" class="form-control" placeholder="<?= LanguageHelper::trans('payroll.enter_bonus') ?>" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="payroll_detail_deductions" class="form-label"><?= LanguageHelper::trans('payroll.deductions') ?></label>
                            <input type="number" id="payroll_detail_deductions" class="form-control" placeholder="<?= LanguageHelper::trans('payroll.enter_deductions') ?>" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="payroll_detail_insurance" class="form-label"><?= LanguageHelper::trans('payroll.insurance') ?></label>
                            <input type="number" id="payroll_detail_insurance" class="form-control" placeholder="<?= LanguageHelper::trans('payroll.enter_insurance') ?>" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="payroll_detail_payment_date" class="form-label"><?= LanguageHelper::trans('payroll.payment_date') ?></label>
                            <input type="date" id="payroll_detail_payment_date" class="form-control" required>
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
        employee_id: <?= $employee['id'] ?>,
        payroll_id: <?= $payroll['id'] ?>,
        payroll_month: null,
        salary: null,
        bonus: null,
        deductions: null,
        deductions: null,
        insurance: null,
        net_salary: null,
        base_salary: null,
        payment_date: null,
    }
    var base_salary = <?= $payroll['base_salary'] ?>;

    function handleDelete(data) {
        let cf = confirm(`<?= LanguageHelper::trans('payroll.confirm_delete_detail') ?>`)
        if (cf) {
            $.ajax({
                url: `<?=url('payroll-detail')?>/delete/${data.id}`,
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
            $('#payroll_detail_base_salary').val(base_salary)
        }  else {
            dataModel = data
            $('#payroll_detail_base_salary').val(dataModel.base_salary)
            for (let i in dataModel) {
                $(`#payroll_detail_${i}`).val(dataModel[i])
                if (i == 'payroll_month') {
                    getSumWorkPoint(dataModel[i])
                }
            }
        }
        action = type
        $('#modal').modal('show')
    }

    function handleSave() {
        if (action == 1) {
            for (let i in dataModel) {
                if ($(`#payroll_detail_${i}`).length) {
                    dataModel[i] = $(`#payroll_detail_${i}`).val()
                }
            }
            delete dataModel.id
            $.ajax({
                url: `<?=url('payroll-detail')?>/create`,
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
                if ($(`#payroll_detail_${i}`).length) {
                    dataModel[i] = $(`#payroll_detail_${i}`).val()
                }
            }
            $.ajax({
                url: `<?=url('payroll-detail')?>/update`,
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

    function getSumWorkPoint(payroll_month) {
        $.ajax({
            url: `<?=url('attendance')?>/sumByMonth`,
            type: "post",
            data: {
                payroll_month: payroll_month,
                employee_id: <?= $employee['id'] ?>
            },
            dataType: "application/json",
            success: (data) => {
            },
            error: (error) => {
                const json = JSON.parse(error.responseText)
                const sum_work_point = json?.data?.sum_work_point
                const base_salary = $('#payroll_detail_base_salary').val()
                const net_salary = Math.round((base_salary / 22) * sum_work_point)
                $('#div-salary').html(`
                    <div class="col-12 mb-3">
                        <label for="payroll_detail_salary" class="form-label">Lương thực nhận (${sum_work_point} ngày công)</label>
                        <input type="number" id="payroll_detail_salary" value="${net_salary}" class="form-control" placeholder="<?= LanguageHelper::trans('payroll.enter_base_salary') ?>" required>
                    </div>
                `)
            }
        });
    }

    $('#payroll_detail_payroll_month').change(function(e) {
        const payroll_month = e.target.value
        getSumWorkPoint(payroll_month)
    })
</script>
<?php endblock()?>