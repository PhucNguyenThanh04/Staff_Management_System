<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <!-- <form method="get">
                <div class="nav-item d-flex align-items-center">
                    <i class="bx bx-search fs-4 lh-0"></i>
                    <input type="text" name="search" class="form-control border-0 shadow-none" placeholder="Tìm kiếm..." aria-label="Search..." value="<?=isset($_GET['search']) ? $_GET['search'] : ''?>" />
                </div>
            </form> -->
        </div>
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="<?=asset('assets/img/user.jpg')?>" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="<?=asset('assets/img/user.jpg')?>" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block"><?=Auth::getUser('mvc_employee')['name']?></span>
                                    <small class="text-muted"><?= LanguageHelper::trans('employee_role.' . getRoleLabel(Auth::getUser('mvc_employee')['role'])) ?></small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= url('profile') ?>">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle"><?= LanguageHelper::trans('common.account_info') ?></span>
                        </a>
                    </li>
                    <!-- <li>
                        <a class="dropdown-item" href="#">
                            <i class="bx bx-cog me-2"></i>
                            <span class="align-middle">Settings</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <span class="d-flex align-items-center align-middle">
                                <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                                <span class="flex-grow-1 align-middle">Billing</span>
                                <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                            </span>
                        </a>
                    </li> -->
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?=url('auth/logout')?>">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle"><?= LanguageHelper::trans('common.logout') ?></span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>