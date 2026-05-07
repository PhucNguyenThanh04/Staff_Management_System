<?php
require_once 'views/layouts/default.php';

function getAssetCategoryLabel($cat) {
    return [1 => 'Laptop', 2 => 'Điện thoại', 3 => 'Thẻ ra vào', 4 => 'Khác'][$cat] ?? '';
}
function getAssetStatusLabel($st) {
    return [1 => 'Sẵn sàng', 2 => 'Đang cấp', 3 => 'Bảo trì', 4 => 'Thanh lý'][$st] ?? '';
}
?>
<?php startblock('title')?>Quản lý tài sản<?php endblock()?>

<?php startblock('content')?>
<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-header">Danh sách tài sản</h5>
        <div class="card-header">
            <button class="btn btn-primary" onclick="openModal(1)">Thêm tài sản</button>
        </div>
    </div>
    <div class="card-body pt-0">
        <form class="row">
            <div class="form-group col-2">
                <label>Mã / Tên</label>
                <input type="text" name="search" class="form-control" placeholder="Tìm..." value="<?=$_GET['search']??''?>">
            </div>
            <div class="form-group col-2">
                <label>Loại</label>
                <select name="category" class="form-control">
                    <option value="">Tất cả</option>
                    <?php foreach ([1=>'Laptop',2=>'Điện thoại',3=>'Thẻ ra vào',4=>'Khác'] as $k=>$v): ?>
                        <option value="<?=$k?>" <?=isset($_GET['category'])&&$_GET['category']==$k?'selected':''?>><?=$v?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="form-group col-2">
                <label>Trạng thái</label>
                <select name="status" class="form-control">
                    <option value="">Tất cả</option>
                    <?php foreach ([1=>'Sẵn sàng',2=>'Đang cấp',3=>'Bảo trì',4=>'Thanh lý'] as $k=>$v): ?>
                        <option value="<?=$k?>" <?=isset($_GET['status'])&&$_GET['status']==$k?'selected':''?>><?=$v?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="mt-3 col-3 d-flex align-items-end">
                <button class="btn btn-info" type="submit">Lọc</button>
                <a href="<?=url('asset')?>" class="btn btn-warning ms-2">Xóa lọc</a>
            </div>
        </form>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Mã TS</th>
                    <th>Tên</th>
                    <th>Loại</th>
                    <th>Hãng/Mẫu</th>
                    <th>Serial</th>
                    <th>Giá trị</th>
                    <th>Ngày mua</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assets as $asset): ?>
                <tr>
                    <td><?=$asset['code']?></td>
                    <td><?=$asset['name']?></td>
                    <td><?=getAssetCategoryLabel($asset['category'])?></td>
                    <td><?=$asset['brand']?> <?=$asset['model']?></td>
                    <td><?=$asset['serial_number']?></td>
                    <td><?=number_format($asset['value'])?> đ</td>
                    <td><?=$asset['purchase_date'] ? dateFromStr($asset['purchase_date'], 'd/m/Y') : ''?></td>
                    <td><?=getAssetStatusLabel($asset['status'])?></td>
                    <td>
                        <a href="javascript:void(0);" class="text-info" onclick='openModal(2, <?=json_encode($asset)?>)'><i class="bx bx-edit-alt me-1"></i> Sửa</a>
                        <a href="javascript:void(0);" onclick='handleDelete(<?=json_encode($asset)?>)' class="text-danger ms-2"><i class="bx bx-trash me-1"></i> Xóa</a>
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
                    <h5 class="modal-title">Thêm / Cập nhật tài sản</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="asset_code">Mã tài sản</label>
                            <input type="text" id="asset_code" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="asset_name">Tên tài sản</label>
                            <input type="text" id="asset_name" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="asset_category">Loại</label>
                            <select id="asset_category" class="form-control">
                                <option value="1">Laptop</option>
                                <option value="2">Điện thoại</option>
                                <option value="3">Thẻ ra vào</option>
                                <option value="4">Khác</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="asset_brand">Hãng</label>
                            <input type="text" id="asset_brand" class="form-control">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="asset_model">Model</label>
                            <input type="text" id="asset_model" class="form-control">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="asset_serial_number">Số serial</label>
                            <input type="text" id="asset_serial_number" class="form-control">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="asset_value">Giá trị (VND)</label>
                            <input type="number" id="asset_value" class="form-control" value="0">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="asset_purchase_date">Ngày mua</label>
                            <input type="date" id="asset_purchase_date" class="form-control">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="asset_status">Trạng thái</label>
                            <select id="asset_status" class="form-control">
                                <option value="1">Sẵn sàng</option>
                                <option value="2">Đang cấp</option>
                                <option value="3">Bảo trì</option>
                                <option value="4">Thanh lý</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="asset_note">Ghi chú</label>
                            <textarea id="asset_note" class="form-control" rows="2"></textarea>
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
    id: null, code: '', name: '', category: 1, brand: '', model: '',
    serial_number: '', value: 0, purchase_date: '', status: 1, note: ''
};

function openModal(type, data = null) {
    if (type === 1) {
        document.querySelector('#modal form')?.reset();
    } else {
        dataModel = data;
        for (let k in dataModel) {
            if (k === 'purchase_date' && dataModel[k] === '0000-00-00') dataModel[k] = '';
            $(`#asset_${k}`).val(dataModel[k]);
        }
    }
    action = type;
    $('#modal').modal('show');
}

function handleDelete(data) {
    if (confirm('Xóa tài sản này?')) {
        $.ajax({
            url: `<?=url('asset')?>/delete/${data.id}`,
            success: () => window.location.reload(),
            error: () => window.location.reload()
        });
    }
}

function handleSave() {
    for (let k in dataModel) {
        if ($(`#asset_${k}`).length) dataModel[k] = $(`#asset_${k}`).val();
    }
    const url = action === 1 ? `<?=url('asset')?>/create` : `<?=url('asset')?>/update`;
    $.ajax({ url, type: "post", data: dataModel, success: () => window.location.reload(), error: () => window.location.reload() });
    return false;
}
</script>
<?php endblock()?>