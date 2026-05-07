<?php
require_once 'views/layouts/default.php';

function getRdTypeLabel($type) {
    $labels = [
        1 => 'Khen thưởng',
        2 => 'Kỷ luật',
        3 => 'Cảnh cáo',
        4 => 'Sáng kiến',
    ];
    return $labels[$type] ?? 'Không xác định';
}
?>
<?php startblock('title')?>Khen thưởng & Kỷ luật<?php endblock()?>

<?php startblock('content')?>
<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-header">Quản lý khen thưởng / kỷ luật</h5>
        <?php if (isRoleAdmin() || isRoleNhansu()): ?>
        <div class="card-header">
            <button class="btn btn-primary" onclick="openModal(1)">Thêm mới</button>
        </div>
        <?php endif ?>
    </div>
    <div class="card-body pt-0">
        <form class="row">
            <div class="form-group col-3">
                <label>Nhân viên</label>
                <select id="search_employee_id" name="employee_id" class="form-control" style="width:100%"></select>
            </div>
            <div class="form-group col-2">
                <label>Loại</label>
                <select name="rd_type" class="form-control">
                    <option value="">Tất cả</option>
                    <option value="1">Khen thưởng</option>
                    <option value="2">Kỷ luật</option>
                    <option value="3">Cảnh cáo</option>
                    <option value="4">Sáng kiến</option>
                </select>
            </div>
            <div class="form-group col-2">
                <label>Trạng thái</label>
                <select name="status" class="form-control">
                    <option value="">Tất cả</option>
                    <option value="pending">Chờ duyệt</option>
                    <option value="approved">Đã duyệt</option>
                    <option value="rejected">Từ chối</option>
                </select>
            </div>
            <div class="mt-3 col-3 d-flex align-items-end">
                <button class="btn btn-info" type="submit">Lọc</button>
                <a href="<?=url('reward-discipline')?>" class="btn btn-warning ms-2">Xóa lọc</a>
            </div>
        </form>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Nhân viên</th>
                    <th>Loại</th>
                    <th>Tiêu đề</th>
                    <th>Lý do</th>
                    <th>Số tiền</th>
                    <th>Ngày hiệu lực</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php foreach ($rewards_disciplines as $item): ?>
                    <tr>
                        <td><?=$item['employee_name']?> (<?=$item['employee_email']?>)</td>
                        <td><?=getRdTypeLabel($item['rd_type'])?></td>
                        <td><?=$item['title']?></td>
                        <td><?=$item['reason']?></td>
                        <td><?=number_format($item['amount'])?> đ</td>
                        <td><?=dateFromStr($item['effective_date'], 'd/m/Y')?></td>
                        <td>
                            <?php
                                $statusLabels = ['pending'=>'Chờ duyệt', 'approved'=>'Đã duyệt', 'rejected'=>'Từ chối'];
                                echo $statusLabels[$item['status']] ?? $item['status'];
                            ?>
                        </td>
                        <td>
                            <?php if (isRoleAdmin() || isRoleNhansu()): ?>
                                <a href="javascript:void(0);" class="text-info" onclick='openModal(2, <?=json_encode($item)?>)'><i class="bx bx-edit-alt me-1"></i> Sửa</a>
                                <a href="javascript:void(0);" onclick='handleDelete(<?=json_encode($item)?>)' class="text-danger ms-2"><i class="bx bx-trash me-1"></i> Xóa</a>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach?>
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
                    <h5 class="modal-title">Thêm / Cập nhật</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="rd_employee_id" class="form-label">Nhân viên</label>
                            <select id="rd_employee_id" class="form-control" required style="width:100%;"></select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="rd_rd_type" class="form-label">Loại</label>
                            <select id="rd_rd_type" class="form-control" required>
                                <option value="1">Khen thưởng</option>
                                <option value="2">Kỷ luật</option>
                                <option value="3">Cảnh cáo</option>
                                <option value="4">Sáng kiến</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="rd_title" class="form-label">Tiêu đề</label>
                            <input type="text" id="rd_title" class="form-control" placeholder="Nhập tiêu đề" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="rd_reason" class="form-label">Lý do / Căn cứ</label>
                            <textarea id="rd_reason" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="rd_amount" class="form-label">Giá trị thưởng/phạt (VND)</label>
                            <input type="number" id="rd_amount" class="form-control" value="0">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="rd_effective_date" class="form-label">Ngày hiệu lực</label>
                            <input type="date" id="rd_effective_date" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="rd_status" class="form-label">Trạng thái</label>
                            <select id="rd_status" class="form-control" required>
                                <option value="pending">Chờ duyệt</option>
                                <option value="approved">Đã duyệt</option>
                                <option value="rejected">Từ chối</option>
                            </select>
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
var action = 1; // 1: create, 2: update
var dataModel = {
    id: null, employee_id: null, rd_type: 1, title: '',
    reason: '', amount: 0, effective_date: '', status: 'pending'
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
                results: data.data.map(item => ({ id: item.id, text: `${item.name} (${item.email})` }))
            };
        }
    });
});

function openModal(type, data = null) {
    if (type === 1) {
        document.querySelector('#modal form')?.reset();
        $('#rd_employee_id').val(null).trigger('change');
        setupSelect2({
            target: '#rd_employee_id',
            searchUrl: '<?= url('employee/search') ?>',
            processResults: (data) => ({ results: data.data.map(item => ({ id: item.id, text: `${item.name} (${item.email})` })) })
        });
    } else {
        dataModel = data;
        $('#rd_employee_id').html(`<option value="${dataModel.employee_id}">${dataModel.employee_name} (${dataModel.employee_email})</option>`).select2({ disabled: true });
        for (let k in dataModel) {
            if (k !== 'employee_id') $(`#rd_${k}`).val(dataModel[k]);
        }
    }
    action = type;
    $('#modal').modal('show');
}

function handleDelete(data) {
    if (confirm('Xóa bản ghi này?')) {
        $.ajax({
            url: `<?=url('reward-discipline')?>/delete/${data.id}`,
            success: () => window.location.reload(),
            error: () => window.location.reload()
        });
    }
}

function handleSave() {
    for (let k in dataModel) {
        if ($(`#rd_${k}`).length) dataModel[k] = $(`#rd_${k}`).val();
    }
    const url = action === 1 ? `<?=url('reward-discipline')?>/create` : `<?=url('reward-discipline')?>/update`;
    $.ajax({ url, type: "post", data: dataModel, success: () => window.location.reload(), error: () => window.location.reload() });
    return false;
}
</script>
<?php endblock()?>