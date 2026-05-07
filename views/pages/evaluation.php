<?php
require_once 'views/layouts/default.php';

function getEvalTypeLabel($type) {
    $labels = [1 => 'Tháng', 2 => 'Quý', 3 => 'Năm'];
    return $labels[$type] ?? 'Không xác định';
}
function getEvalStatusLabel($status) {
    $labels = ['draft' => 'Bản nháp', 'submitted' => 'Đã trình', 'approved' => 'Đã duyệt'];
    return $labels[$status] ?? $status;
}
?>
<?php startblock('title')?>
Đánh giá nhân viên
<?php endblock()?>

<?php startblock('content')?>
<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-header">Quản lý đánh giá</h5>
        <?php if (isRoleAdmin() || isRoleNhansu()): ?>
        <div class="card-header">
            <button class="btn btn-primary" onclick="openModal(1)"><?= LanguageHelper::trans('common.create') ?></button>
        </div>
        <?php endif ?>
    </div>
    <div class="card-body pt-0">
        <form class="row">
            <div class="form-group col-3">
                <label><?= LanguageHelper::trans('employee.title') ?></label>
                <select id="search_employee_id" name="employee_id" style="width:100%"></select>
            </div>
            <div class="form-group col-2">
                <label>Loại</label>
                <select name="eval_type" class="form-control">
                    <option value="">Tất cả</option>
                    <option value="1" <?=isset($_GET['eval_type']) && $_GET['eval_type']==1?'selected':''?>>Tháng</option>
                    <option value="2" <?=isset($_GET['eval_type']) && $_GET['eval_type']==2?'selected':''?>>Quý</option>
                    <option value="3" <?=isset($_GET['eval_type']) && $_GET['eval_type']==3?'selected':''?>>Năm</option>
                </select>
            </div>
            <div class="form-group col-2">
                <label>Kỳ đánh giá</label>
                <input type="text" name="period" class="form-control" placeholder="2025-04 / 2025-Q2" value="<?=isset($_GET['period']) ? $_GET['period'] : ''?>">
            </div>
            <div class="mt-3 col-3 d-flex align-items-end">
                <button class="btn btn-info" type="submit"><?= LanguageHelper::trans('common.filter') ?></button>
                <a href="<?=url('evaluation')?>" class="btn btn-warning" style="margin-left:5px;"><?= LanguageHelper::trans('common.clear_filter') ?></a>
            </div>
        </form>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th><?= LanguageHelper::trans('employee.title') ?></th>
                    <th>Loại</th>
                    <th>Kỳ</th>
                    <th>Ngày đánh giá</th>
                    <th>Điểm</th>
                    <th>Trạng thái</th>
                    <th><?= LanguageHelper::trans('common.action') ?></th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php foreach ($evaluations as $eval): ?>
                    <tr>
                        <td><?=$eval['employee_name']?> (<?=$eval['employee_email']?>)</td>
                        <td><?=getEvalTypeLabel($eval['eval_type'])?></td>
                        <td><?=$eval['period']?></td>
                        <td><?=dateFromStr($eval['eval_date'], 'd/m/Y')?></td>
                        <td><?=$eval['score']?></td>
                        <td><?=getEvalStatusLabel($eval['status'])?></td>
                        <td>
                            <?php if (isRoleAdmin() || isRoleNhansu()): ?>
                                <a href="javascript:void(0);" class="text-info" onclick='openModal(2, <?=json_encode($eval)?>)'><i class="bx bx-edit-alt me-1 text-info"></i> <?= LanguageHelper::trans('common.edit') ?></a>
                                <a href="javascript:void(0);" onclick='handleDelete(<?=json_encode($eval)?>)' class="text-danger ms-2"><i class="bx bx-trash me-1 text-danger"></i> <?= LanguageHelper::trans('common.delete') ?></a>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach?>
            </tbody>
        </table>
        <!-- Phân trang đặt trong table-responsive nhưng phải có div bao ngoài để không tràn -->
        <?php if (isset($pagination) && $pagination): ?>
            <div class="d-flex justify-content-center mt-3">
                <?= $pagination ?>
            </div>
        <?php endif ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form onsubmit="return handleSave()">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm / Cập nhật đánh giá</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="eval_employee_id" class="form-label"><?= LanguageHelper::trans('employee.title') ?></label>
                            <select id="eval_employee_id" class="form-control" required style="width:100%;"></select>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="eval_eval_type" class="form-label">Loại</label>
                            <select id="eval_eval_type" class="form-control" required>
                                <option value="1">Tháng</option>
                                <option value="2">Quý</option>
                                <option value="3">Năm</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="eval_period" class="form-label">Kỳ</label>
                            <input type="text" id="eval_period" class="form-control" placeholder="2025-04" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="eval_eval_date" class="form-label">Ngày đánh giá</label>
                            <input type="date" id="eval_eval_date" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="eval_score" class="form-label">Điểm (0-10)</label>
                            <input type="number" id="eval_score" class="form-control" min="0" max="10" step="0.1">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="eval_content" class="form-label">Nhận xét chung</label>
                            <textarea id="eval_content" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="eval_strengths" class="form-label">Điểm mạnh</label>
                            <textarea id="eval_strengths" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="eval_weaknesses" class="form-label">Điểm cần cải thiện</label>
                            <textarea id="eval_weaknesses" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="eval_status" class="form-label">Trạng thái</label>
                            <select id="eval_status" class="form-control" required>
                                <option value="draft">Bản nháp</option>
                                <option value="submitted">Đã trình</option>
                                <option value="approved">Đã duyệt</option>
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

<script>
var action = 1; // 1: create, 2: update
var dataModel = {
    id: null,
    employee_id: null,
    reviewer_id: <?= Auth::getUser('mvc_employee')['id'] ?? 'null' ?>,
    eval_type: 1,
    period: '',
    eval_date: '',
    score: null,
    content: '',
    strengths: '',
    weaknesses: '',
    status: 'draft'
};

document.addEventListener("DOMContentLoaded", function () {
    setupSelect2({
        target: '#search_employee_id',
        searchUrl: '<?= url('employee/search') ?>',
        selectedItem: {
            id: '<?= isset($_GET['employee_id']) ? $_GET['employee_id'] : '' ?>',
            text: '<?= isset($_GET['employee_name']) ? $_GET['employee_name'] : '' ?>'
        },
        processResults: function (data) {
            return {
                results: data.data.map(item => ({ id: item.id, text: `${item.name || ''} (${item.email})` }))
            };
        }
    });
});

function openModal(type, data = null) {
    if (type === 1) {
        document.querySelector('#modal form')?.reset();
        $('#eval_employee_id').val(null).trigger('change');
        document.getElementById('eval_eval_date').value = new Date().toISOString().split('T')[0];
        setupSelect2({
            target: '#eval_employee_id',
            searchUrl: '<?= url('employee/search') ?>',
            processResults: function (data) {
                return {
                    results: data.data.map(item => ({ id: item.id, text: `${item.name || ''} (${item.email})` }))
                };
            }
        });
        dataModel = {
            id: null,
            employee_id: null,
            reviewer_id: <?= Auth::getUser('mvc_employee')['id'] ?? 'null' ?>,
            eval_type: 1,
            period: '',
            eval_date: document.getElementById('eval_eval_date').value,
            score: null,
            content: '',
            strengths: '',
            weaknesses: '',
            status: 'draft'
        };
    } else {
        dataModel = data;
        $('#eval_employee_id').html(`<option value="${dataModel.employee_id}">${dataModel.employee_name || ''} (${dataModel.employee_email})</option>`).select2({ disabled: true });
        for (let key in dataModel) {
            if (key === 'employee_id' || key === 'reviewer_id') continue;
            if (key === 'eval_date' && dataModel[key] === '0000-00-00') dataModel[key] = '';
            $(`#eval_${key}`).val(dataModel[key]);
        }
    }
    action = type;
    $('#modal').modal('show');
}

function handleDelete(data) {
    if (confirm('Bạn có chắc chắn muốn xóa đánh giá này?')) {
        $.ajax({
            url: `<?=url('evaluation')?>/delete/${data.id}`,
            success: () => window.location.reload(),
            error: () => window.location.reload()
        });
    }
}

function handleSave() {
    dataModel.employee_id = $('#eval_employee_id').val();
    dataModel.eval_type = $('#eval_eval_type').val();
    dataModel.period = $('#eval_period').val();
    dataModel.eval_date = $('#eval_eval_date').val();
    dataModel.score = $('#eval_score').val();
    dataModel.content = $('#eval_content').val();
    dataModel.strengths = $('#eval_strengths').val();
    dataModel.weaknesses = $('#eval_weaknesses').val();
    dataModel.status = $('#eval_status').val();

    const url = action === 1 ? `<?=url('evaluation')?>/create` : `<?=url('evaluation')?>/update`;
    $.ajax({
        url,
        type: "post",
        data: dataModel,
        success: () => window.location.reload(),
        error: () => window.location.reload()
    });
    return false;
}
</script>
<?php endblock()?>