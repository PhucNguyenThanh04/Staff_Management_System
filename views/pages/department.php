<?php
require_once 'views/layouts/default.php';
?>
<?php startblock('title')?>
<?= LanguageHelper::trans('department.title') ?>
<?php endblock()?>

<?php startblock('content')?>
<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-header"><?= LanguageHelper::trans('department.title') ?></h5>
        <div class="card-header">
            <button class="btn btn-primary" onclick="openModal(1)"><?= LanguageHelper::trans('common.create') ?></button>
        </div>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th><?= LanguageHelper::trans('department.name') ?></th>
                    <th><?= LanguageHelper::trans('common.update_date') ?></th>
                    <th><?= LanguageHelper::trans('common.action') ?></th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php foreach ($departments as $department): ?>
                    <tr>
                        <td><?=$department['name']?></td>
                        <td><?=$department['updated_at'] ? dateFromStr($department['updated_at'], 'd/m/Y') : ''?></td>
                        <td>
                            <a href="javascript:void(0);" class="text-info" onclick='openModal(2, <?=json_encode($department)?>)'><i class="bx bx-edit-alt me-1 text-info"></i> <?= LanguageHelper::trans('common.edit') ?></a>
                            <a href="javascript:void(0);" onclick='handleDelete(<?=json_encode($department)?>)' class="text-danger ms-4"><i class="bx bx-trash me-1 text-danger"></i> <?= LanguageHelper::trans('common.delete') ?></a>
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
                    <h5 class="modal-title" id="modalCenterTitle"><?= LanguageHelper::trans('department.create_update') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="department_name" class="form-label"><?= LanguageHelper::trans('department.name') ?></label>
                            <input type="text" id="department_name" class="form-control" placeholder="<?= LanguageHelper::trans('department.enter_name') ?>" required>
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
        created_at: null,
        updated_at: null
    }

    function handleDelete(data) {
        let cf = confirm(`<?= LanguageHelper::trans('department.confirm_delete') ?>: ${data.name}`)
        if (cf) {
            $.ajax({
                url: `<?=url('department')?>/delete/${data.id}`,
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
            $('#department_name').val(null)
        }  else {
            dataModel = data
            $('#department_name').val(dataModel.name)
        }
        action = type
        $('#modal').modal('show')
    }

    function handleSave() {
        if (action == 1) {
            delete dataModel.id
            dataModel.created_at = new Date().toISOString().slice(0, 19).replace('T', ' ');
            dataModel.updated_at = new Date().toISOString().slice(0, 19).replace('T', ' ');
            dataModel.name = $('#department_name').val()
            $.ajax({
                url: `<?=url('department')?>/create`,
                type: "post",
                data: dataModel,
                dataType: "application/json",
                success: (data) => {},
                error: (error) => {
                    window.location.reload()
                }
            });
        } else {
            dataModel.name = $('#department_name').val()
            dataModel.updated_at = new Date().toISOString().slice(0, 19).replace('T', ' ');
            $.ajax({
                url: `<?=url('department')?>/update`,
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