<?php
require_once('core/Auth.php');
function isActive($controller, $excepts = [])
{
    $path = explode('?', $_SERVER['REQUEST_URI']);
    $parts = explode('/', $path[0]);
    $currentPart = $parts[array_key_last($parts)];
    if (strpos($path[0], $controller) && !in_array($currentPart, $excepts)) {
        return 'active';
    }
}

function isDefault()
{
    $val = (explode('/', $_SERVER['REQUEST_URI']));
    if (count($val) == 3 && !$val[0] && !$val[2]) {
        return 'active';
    }
}
?>
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo d-flex align-items-center my-1">
        <a href="<?=url('')?>" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img width="60px" style="border-radius: 5px;" src="<?=asset('assets/img/logo_quanlynhansu.png')?>" alt="">
            </span>
        </a>
        <h4 class="m-0" style="padding-left: 15px;">
            quanlynhansu
        </h4>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item <?=isActive('dashboard') . isDefault()?>">
            <a href="<?=url('dashboard')?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics"><?= LanguageHelper::trans('common.dashboard') ?></div>
            </a>
        </li>
        <?php if (checkScope('attendance')): ?>
            <li class="menu-item <?=isActive('attendance')?>">
                <a href="<?=url('attendance')?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar"></i>
                    <div data-i18n="Basic"><?= LanguageHelper::trans('common.attendance') ?></div>
                </a>
            </li>
        <?php endif ?>
        <?php if (checkScope('payroll')): ?>
            <li class="menu-item <?=isActive('payroll')?>">
                <a href="<?=url('payroll')?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-dollar-circle"></i>
                    <div data-i18n="Basic"><?= LanguageHelper::trans('common.payroll') ?></div>
                </a>
            </li>
        <?php endif ?>
        <?php if (checkScope('employee')): ?>
            <li class="menu-item <?=isActive('employee')?>">
                <a href="<?=url('employee')?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="Basic"><?= LanguageHelper::trans('common.employee') ?></div>
                </a>
            </li>
        <?php endif ?>
        <?php if (checkScope('contract')): ?>
            <li class="menu-item <?=isActive('contract', ['contract-type'])?>">
                <a href="<?=url('contract')?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-dock-top"></i>
                    <div data-i18n="Basic"><?= LanguageHelper::trans('common.contract') ?></div>
                </a>
            </li>
        <?php endif ?>
        <?php if (checkScope('department')): ?>
            <li class="menu-item <?=isActive('department')?>">
                <a href="<?=url('department')?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-building-house"></i>
                    <div data-i18n="Basic"><?= LanguageHelper::trans('common.department') ?></div>
                </a>
            </li>
        <?php endif ?>
    </ul>
</aside>