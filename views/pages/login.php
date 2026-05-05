<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?= LanguageHelper::trans('login.login_title') ?></title>
    <link rel="icon" type="image/x-icon" href="<?=asset('assets/img/logo_quanlynhansu.png')?>" />
    <link rel="stylesheet" href="<?=asset('assets/vendor/fonts/boxicons.css')?>" />
    <link rel="stylesheet" href="<?=asset('assets/vendor/css/core.css')?>" />
    <link rel="stylesheet" href="<?=asset('assets/vendor/css/theme-default.css')?>" />
    <link rel="stylesheet" href="<?=asset('assets/vendor/css/pages/page-auth.css')?>" />
    <link rel="stylesheet" href="<?=asset('assets/vendor/css/toastr.min.css')?>" />
</head>

<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card">
                    <div class="card-body">
                        <div class="app-brand justify-content-center">
                            <a href="index.html" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <img width="150px" src="<?=asset('assets/img/logo_quanlynhansu.png')?>" alt="">
                                </span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-2 text-center" style="line-height: 30px">
                            <?= LanguageHelper::trans('login.login_title') ?>
                        </h4>
                        <p class="mb-4 text-center"><?= LanguageHelper::trans('login.login_to_continue') ?></p>
                        <p class="text-danger text-center">
                            <?php if (Flash::has('error')): ?>
                                <?= Flash::get('error') ?>
                            <?php endif ?>
                        </p>
                        <form id="formAuthentication" action="<?=url('auth/handleLogin')?>" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label"><?= LanguageHelper::trans('common.email') ?></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="<?= LanguageHelper::trans('login.enter_email') ?>" autofocus required />
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password"><?= LanguageHelper::trans('common.password') ?></label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember" />
                                    <label class="form-check-label" for="remember-me"><?= LanguageHelper::trans('common.remember') ?></label>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-primary d-grid w-100" type="submit"><?= LanguageHelper::trans('common.login') ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="language-selector" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
        <select onchange="window.location.href='<?= url('language/change') ?>?lang=' + this.value + '&redirect_url=' + encodeURIComponent(window.location.href)">
            <?php foreach (LanguageHelper::getAvailableLanguages() as $code => $name): ?>
            <option value="<?php echo $code; ?>" <?= LanguageHelper::getCurrentLang() === $code ? 'selected' : ''; ?>>
                <?php echo $name; ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    <script src="<?=asset('assets/vendor/libs/jquery/jquery.js')?>"></script>
    <script src="<?=asset('assets/vendor/libs/popper/popper.js')?>"></script>
    <script src="<?=asset('assets/vendor/js/bootstrap.js')?>"></script>
    <script src="<?=asset('assets/vendor/js/menu.js')?>"></script>
    <script src="<?=asset('assets/vendor/js/helpers.js')?>"></script>
    <script src="<?=asset('assets/js/config.js')?>"></script>
    <script src="<?=asset('assets/js/main.js')?>"></script>

    <!-- <script>
        document.getElementById('email').value = 'admin@gmail.com'
        document.getElementById('password').value = 'admin'
    </script> -->
</body>

</html>