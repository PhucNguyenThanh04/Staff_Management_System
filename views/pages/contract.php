<?php
require_once 'views/layouts/default.php';
function getContractTypeLabel($value) {
    $label = '';
    foreach (contract_types as $contract_type) {
        if ($contract_type['value'] == $value) {
            $label = $contract_type['label'];
        }
    }
    return $label;
}
?>
<?php startblock('title')?>
<?= LanguageHelper::trans('contract.title') ?>
<?php endblock()?>

<?php startblock('content')?>
<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-header"><?= LanguageHelper::trans('contract.manage') ?></h5>
        <div class="card-header">
            <button class="btn btn-primary" onclick="openModal(1)"><?= LanguageHelper::trans('common.create') ?></button>
        </div>
    </div>
    <div class="card-header pt-0">
        <form>
            <input type="text" name="search" class="form-control" placeholder="<?= LanguageHelper::trans('common.search_employee') ?>" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>" />
        </form>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th><?= LanguageHelper::trans('contract.name') ?></th>
                    <th><?= LanguageHelper::trans('employee.title') ?></th>
                    <th><?= LanguageHelper::trans('contract.type') ?></th>
                    <th><?= LanguageHelper::trans('contract.start_date') ?></th>
                    <th><?= LanguageHelper::trans('contract.end_date') ?></th>
                    <th><?= LanguageHelper::trans('common.update_date') ?></th>
                    <th><?= LanguageHelper::trans('common.action') ?></th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php foreach ($contracts as $contract): ?>
                    <tr>
                        <td><?=$contract['name']?></td>
                        <td><?=$contract['employee_name']?> (<?=$contract['employee_email']?>)</td>
                        <td><?= getContractTypeLabel($contract['contract_type']) ?></td>
                        <td><?=$contract['start_date'] ? dateFromStr($contract['start_date'], 'd/m/Y') : 'N/A'?></td>
                        <td><?=$contract['end_date'] && $contract['end_date'] != '0000-00-00' ? dateFromStr($contract['end_date'], 'd/m/Y') : 'N/A'?></td>
                        <td><?=$contract['updated_at'] ? dateFromStr($contract['updated_at'], 'd/m/Y') : 'N/A'?></td>
                        <td>
                            <a href="javascript:void(0);" class="text-info" onclick='openModal(2, <?=json_encode($contract)?>)'><i class="bx bx-edit-alt me-1 text-info"></i> <?= LanguageHelper::trans('common.edit') ?></a>
                            <a href="javascript:void(0);" onclick='handleDelete(<?=json_encode($contract)?>)' class="text-danger ms-4"><i class="bx bx-trash me-1 text-danger"></i> <?= LanguageHelper::trans('common.delete') ?></a>
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
                    <h5 class="modal-title" id="modalCenterTitle"><?= LanguageHelper::trans('contract.create_update') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="contract_name" class="form-label"><?= LanguageHelper::trans('contract.name') ?></label>
                            <input type="text" id="contract_name" class="form-control" placeholder="<?= LanguageHelper::trans('contract.enter_name') ?>" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="contract_contract_type" class="form-label"><?= LanguageHelper::trans('contract.type') ?></label>
                            <select type="text" id="contract_contract_type" class="form-control" required style="width: 100%;">
                                <option value="1">Fulltime</option>
                                <option value="2">Parttime</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="contract_employee_id" class="form-label"><?= LanguageHelper::trans('employee.title') ?></label>
                            <select id="contract_employee_id" class="form-control" required style="width: 100%;"></select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="contract_start_date" class="form-label"><?= LanguageHelper::trans('contract.start_date') ?></label>
                            <input type="date" id="contract_start_date" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="contract_end_date" class="form-label"><?= LanguageHelper::trans('contract.end_date') ?></label>
                            <input type="date" id="contract_end_date" class="form-control">
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
        name: null,
        contract_type: null,
        employee_id: null,
        start_date: null,
        end_date: null,
    }

    function handleDelete(data) {
        let cf = confirm(`<?= LanguageHelper::trans('contract.confirm_delete') ?>: ${data.name}`)
        if (cf) {
            $.ajax({
                url: `<?=url('contract')?>/delete/${data.id}`,
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
        }  else {
            dataModel = data
            for (let i in dataModel) {
                if (i == 'employee_id') {
                    $(`#contract_${i}`).html('')
                    $(`#contract_${i}`).append(`
                        <option value="${dataModel.employee_id}">${dataModel.employee_name || ''} (${dataModel.employee_email})</option>
                    `)
                } else {
                    $(`#contract_${i}`).val(dataModel[i])
                }
            }
        }
        action = type
        $('#modal').modal('show')
    }

    function handleSave() {
        if (action == 1) {
            for (let i in dataModel) {
                if ($(`#contract_${i}`).length) {
                    dataModel[i] = $(`#contract_${i}`).val()
                }
            }
            delete dataModel.id
            $.ajax({
                url: `<?=url('contract')?>/create`,
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
                if ($(`#contract_${i}`).length) {
                    dataModel[i] = $(`#contract_${i}`).val()
                }
            }
            $.ajax({
                url: `<?=url('contract')?>/update`,
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

    setupSelect2({
        target: '#contract_employee_id',
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
</script>
<?php endblock()?>