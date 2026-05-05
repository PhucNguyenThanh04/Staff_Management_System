<?php
require_once 'views/layouts/default.php';
?>
<?php startblock('title')?>
<?= LanguageHelper::trans('profile.title') ?>
<?php endblock()?>

<?php startblock('content')?>
<div class="card">
    <h5 class="card-header"><?= LanguageHelper::trans('profile.update') ?></h5>
    <div class="card-body">
        <form method="post" action="<?=url('profile/update')?>">
        <div class="row">
            <div class="col-12 mb-3">
                <label for="employee_department_id" class="form-label"><?= LanguageHelper::trans('department.title') ?></label>
                <select id="employee_department_id" name="department_id" class="form-control" <?=!isRoleAdmin() ? 'disabled' : ''?>>
                    <option value=""><?= LanguageHelper::trans('common.select') ?></option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?=$department['id']?>" <?=$department['id'] == $profile['department_id'] ? 'selected' : ''?>>
                            <?=$department['name']?>
                        </option>
                    <?php endforeach?>
                </select>
            </div>
            <div class="col-12 mb-3">
                <label for="employee_name" class="form-label"><?= LanguageHelper::trans('employee.fullname') ?></label>
                <input type="text" id="employee_name" name="name" class="form-control" placeholder="<?= LanguageHelper::trans('employee.enter_fullname') ?>" value="<?=$profile['name']?>">
            </div>
            <div class="col-12 mb-3">
                <label for="employee_email" class="form-label"><?= LanguageHelper::trans('common.email') ?></label>
                <input type="email" id="employee_email" name="email" class="form-control" placeholder="<?= LanguageHelper::trans('employee.enter_email') ?>" required value="<?=$profile['email']?>" disabled>
            </div>
            <div class="col-12 mb-3">
                <label for="employee_dob" class="form-label"><?= LanguageHelper::trans('employee.birthday') ?></label>
                <input type="date" id="employee_birthday" name="birthday" class="form-control" placeholder="<?= LanguageHelper::trans('employee.enter_birthday') ?>" value="<?=$profile['birthday']?>">
            </div>
            <div class="col-6 mb-3">
                <label for="employee_address" class="form-label"><?= LanguageHelper::trans('employee.address') ?></label>
                <input type="text" id="employee_address" name="address" class="form-control" placeholder="<?= LanguageHelper::trans('employee.enter_address') ?>" value="<?=$profile['address']?>">
            </div>
            <div class="col-6 mb-3">
                <label for="employee_phone_number" class="form-label"><?= LanguageHelper::trans('employee.phone') ?></label>
                <input type="text" max="15" id="employee_phone_number" name="phone_number" class="form-control" placeholder="<?= LanguageHelper::trans('employee.enter_phone') ?>" value="<?=$profile['phone_number']?>">
            </div>
            <div class="col-6 mb-3">
                <label for="employee_gender" class="form-label"><?= LanguageHelper::trans('employee.gender') ?></label>
                <select id="employee_gender" name="gender" class="form-control" >
                    <option value=""><?= LanguageHelper::trans('common.select') ?></option>
                    <option value="male" <?=$profile['gender'] == 'male' ? 'selected' : ''?>><?= LanguageHelper::trans('common.male') ?></option>
                    <option value="female" <?=$profile['gender'] == 'female' ? 'selected' : ''?>><?= LanguageHelper::trans('common.female') ?></option>
                </select>
            </div>
            <div class="col-6 mb-3">
                <label for="employee_cccd" class="form-label"><?= LanguageHelper::trans('employee.id_card') ?></label>
                <input type="text" max="15" id="employee_cccd" name="cccd" class="form-control" placeholder="<?= LanguageHelper::trans('employee.enter_id_card') ?>" value="<?=$profile['cccd']?>">
            </div>
            <div class="col-12 mb-3">
                <label for="employee_password" class="form-label"><?= LanguageHelper::trans('profile.change_password') ?></label>
                <input type="text" max="15" id="employee_password" name="password" class="form-control" placeholder="<?= LanguageHelper::trans('profile.enter_new_password') ?>">
            </div>
            <button type="submit" class="btn btn-info"><?= LanguageHelper::trans('common.save') ?></button>
        </div>
        </form>
    </div>
</div>
<?php endblock()?>