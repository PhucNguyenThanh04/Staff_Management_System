<?php
require_once 'views/layouts/default.php';
?>
<?php startblock('title')?>
<?php echo LanguageHelper::trans('common.dashboard'); ?>
<?php endblock()?>

<?php startblock('content')?>
<?php if (isRoleAdmin() || isRoleNhansu()): ?>
    <div class="row">
        <div class="col-6 mb-4">
            <div class="card" <?=isRoleAdmin() ? "style='cursor: pointer' onClick=\"window.location.href='" . url('department') . "'\"" : ''?>>
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <i class="menu-icon tf-icons bx bx-dock-top"></i>
                    </div>
                    <span class="fw-semibold d-block mb-1"><?php echo LanguageHelper::trans('dashboard.department_count'); ?></span>
                    <h3 class="card-title mb-2"><?=$department_count?></h3>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-6 mb-4">
            <div class="card" <?=isRoleAdmin() ? "style='cursor: pointer' onClick=\"window.location.href='" . url('employee', ['status' => 1]) . "'\"" : ''?>>
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <i class="menu-icon tf-icons bx bx-user-plus"></i>
                    </div>
                    <span><?php echo LanguageHelper::trans('dashboard.working_employees'); ?></span>
                    <h3 class="card-title text-nowrap mb-1"><?=$dilam_count?></h3>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-6 mb-4">
            <div class="card" <?=isRoleAdmin() ? "style='cursor: pointer' onClick=\"window.location.href='" . url('employee', ['status' => 2]) . "'\"" : ''?>>
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <i class="menu-icon tf-icons bx bx-user-minus"></i>
                    </div>
                    <span><?php echo LanguageHelper::trans('dashboard.resigned_employees'); ?></span>
                    <h3 class="card-title text-nowrap mb-1"><?=$nghiviec_count?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <?php foreach ($employee_count as $value): ?>
            <div class="col-lg-6 col-md-12 col-6 mb-4">
                <div class="card" <?=isRoleAdmin() ? "style='cursor: pointer' onClick=\"window.location.href='" . url('employee', ['department_id' => $value['department_id']]) . "'\"" : ''?>>
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <i class='bx bx-building' ></i>
                        </div>
                        <span><?php echo str_replace('{department}', $value['department_name'], LanguageHelper::trans('dashboard.employees_in_department')); ?></span>
                        <h3 class="card-title text-nowrap mb-1"><?=$value['count']?></h3>
                    </div>
                </div>
            </div>
        <?php endforeach?>
    </div>
<?php else: ?>
    <div><?php echo LanguageHelper::trans('dashboard.hr'); ?></div>
<?php endif ?>
<?php endblock()?>