<?php
require_once 'views/layouts/default.php';

function getConditionLabel($c) {
    return [1 => 'Tốt', 2 => 'Trung bình', 3 => 'Kém'][$c] ?? '';
}
?>
<?php startblock('title')?>Cấp phát tài sản<?php endblock()?>

<?php startblock('content')?>
<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-header">Lịch sử cấp phát / thu hồi</h5>
        <div class="card-header">
            <button class="btn btn-primary" onclick="openModal(1)">Cấp phát mới</button>
        </div>
    </div>
    <div class="card-body pt-0">
        <form class="row">
            <div class="form-group col-2">
                <label>Tài sản</label>
                <select id="search_asset_id" name="asset_id" class="form-control" style="width:100%"></select>
            </div>
            <div class="form-group col-2">
                <label>Nhân viên</label>
                <select id="search_employee_id" name="employee_id" class="form-control" style="width:100%"></select>
            </div>
            <div class="form-group col-2">
                <label>Trạng thái</label>
                <select name="status" class="form-control">
                    <option value="">Tất cả</option>
                    <option value="active">Đang mượn</option>
                    <option value="returned">Đã trả</option>
                </select>
            </div>
            <div class="mt-3 col-3 d-flex align-items-end">
                <button class="btn btn-info" type="submit">Lọc</button>
                <a href="<?=url('asset-assignment')?>" class="btn btn-warning ms-2">Xóa lọc</a>
            </div>
        </form>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Tài sản</th>
                    <th>Mã TS</th>
                    <th>Nhân viên</th>
                    <th>Người cấp</th>
                    <th>Ngày cấp</th>
                    <th>Ngày trả</th>
                    <th>Tình trạng cấp</th>
                    <th>Tình trạng trả</th>
                    <th>Ghi chú</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($asset_assignments as $aa): ?>
                <tr>
                    <td><?=$aa['asset_name']?></td>
                    <td><?=$aa['asset_code']?></td>
                    <td><?=$aa['employee_name']?> (<?=$aa['employee_email']?>)</td>
                    <td><?=$aa['assigned_by_name']??'N/A'?></td>
                    <td><?=dateFromStr($aa['assign_date'], 'd/m/Y')?></td>
                    <td><?=$aa['return_date'] ? dateFromStr($aa['return_date'], 'd/m/Y') : '<span class="badge bg-success">Đang dùng</span>'?></td>
                    <td><?=getConditionLabel($aa['condition_out'])?></td>
                    <td><?=$aa['condition_in'] ? getConditionLabel($aa['condition_in']) : ''?></td>
                    <td><?=$aa['note']?></td>
                    <td>
                        <a href="javascript:void(0);" class="text-info" onclick='openModal(2, <?=json_encode($aa)?>)'><i class="bx bx-edit-alt me-1"></i> Sửa</a>
                        <a href="javascript:void(0);" onclick='handleDelete(<?=json_encode($aa)?>)' class="text-danger ms-2"><i class="bx bx-trash me-1"></i> Xóa</a>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <?= $pagination ?? '' ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form onsubmit="return handleSave()">
                <div class="modal-header">
                    <h5 class="modal-title">Cấp phát / Cập nhật</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="aa_asset_id">Tài sản</label>
                            <select id="aa_asset_id" class="form-control" required style="width:100%;"></select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="aa_employee_id">Nhân viên</label>
                            <select id="aa_employee_id" class="form-control" required style="width:100%;"></select>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="aa_assign_date">Ngày cấp</label>
                            <input type="date" id="aa_assign_date" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="aa_return_date">Ngày trả (nếu có)</label>
                            <input type="date" id="aa_return_date" class="form-control">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="aa_condition_out">Tình trạng khi cấp</label>
                            <select id="aa_condition_out" class="form-control">
                                <option value="1">Tốt</option>
                                <option value="2">Trung bình</option>
                                <option value="3">Kém</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="aa_condition_in">Tình trạng khi trả</label>
                            <select id="aa_condition_in" class="form-control">
                                <option value="">Chưa trả</option>
                                <option value="1">Tốt</option>
                                <option value="2">Trung bình</option>
                                <option value="3">Kém</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="aa_note">Ghi chú</label>
                            <textarea id="aa_note" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Thoát</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var action = 1;
var dataModel = {
    id: null, asset_id: null, employee_id: null, assign_date: '',
    return_date: '', condition_out: 1, condition_in: '', note: ''
};

document.addEventListener("DOMContentLoaded", function () {
    // Filter select2
    setupSelect2({
        target: '#search_asset_id',
        searchUrl: '<?= url('asset/search') ?>',
        processResults: (data) => ({ results: data.data.map(a => ({ id: a.id, text: a.name + ' (' + a.code + ')' })) })
    });
    setupSelect2({
        target: '#search_employee_id',
        searchUrl: '<?= url('employee/search') ?>',
        processResults: (data) => ({ results: data.data.map(e => ({ id: e.id, text: e.name + ' (' + e.email + ')' })) })
    });
});

function openModal(type, data = null) {
    if (type === 1) {
        document.querySelector('#modal form')?.reset();
        $('#aa_asset_id, #aa_employee_id').val(null).trigger('change');
        setupSelect2({ target: '#aa_asset_id', searchUrl: '<?= url('asset/search') ?>', processResults: (data) => ({ results: data.data.map(a => ({ id: a.id, text: a.name + ' (' + a.code + ')' })) }) });
        setupSelect2({ target: '#aa_employee_id', searchUrl: '<?= url('employee/search') ?>', processResults: (data) => ({ results: data.data.map(e => ({ id: e.id, text: e.name + ' (' + e.email + ')' })) }) });
    } else {
        dataModel = data;
        // Điền select2
        $('#aa_asset_id').html(`<option value="${dataModel.asset_id}">${dataModel.asset_name} (${dataModel.asset_code})</option>`).select2({ disabled: true });
        $('#aa_employee_id').html(`<option value="${dataModel.employee_id}">${dataModel.employee_name} (${dataModel.employee_email})</option>`).select2({ disabled: true });
        for (let k in dataModel) {
            if (k === 'return_date' && dataModel[k] === '0000-00-00') dataModel[k] = '';
            if (k !== 'asset_id' && k !== 'employee_id') $(`#aa_${k}`).val(dataModel[k]);
        }
    }
    action = type;
    $('#modal').modal('show');
}

function handleDelete(data) {
    if (confirm('Xóa bản ghi cấp phát này?')) {
        $.ajax({
            url: `<?=url('asset-assignment')?>/delete/${data.id}`,
            success: () => window.location.reload(),
            error: () => window.location.reload()
        });
    }
}

function handleSave() {
    for (let k in dataModel) {
        if ($(`#aa_${k}`).length) dataModel[k] = $(`#aa_${k}`).val();
    }
    const url = action === 1 ? `<?=url('asset-assignment')?>/create` : `<?=url('asset-assignment')?>/update`;
    $.ajax({ url, type: "post", data: dataModel, success: () => window.location.reload(), error: () => window.location.reload() });
    return false;
}
</script>
<?php endblock()?>