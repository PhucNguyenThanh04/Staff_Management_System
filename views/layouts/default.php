<?php
require_once ('core/Flash.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?php defineblock('title')?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?=asset('assets/img/logo_quanlynhansu.png')?>" />
    <link rel="stylesheet" href="<?=asset('assets/vendor/fonts/boxicons.css')?>" />
    <link rel="stylesheet" href="<?=asset('assets/vendor/css/core.css')?>" />
    <link rel="stylesheet" href="<?=asset('assets/vendor/css/theme-default.css')?>" />
    <link rel="stylesheet" href="<?=asset('assets/vendor/css/toastr.min.css')?>" />
    <link rel="stylesheet" href="<?=asset('assets/vendor/libs/select2/css/select2.min.css')?>" />
    <script src="<?=asset('assets/vendor/libs/jquery/jquery.js')?>"></script>
    <script src="<?=asset('assets/vendor/libs/popper/popper.js')?>"></script>
    <script src="<?=asset('assets/vendor/js/bootstrap.js')?>"></script>
    <script src="<?=asset('assets/vendor/js/menu.js')?>"></script>
    <script src="<?=asset('assets/vendor/js/helpers.js')?>"></script>
    <script src="<?=asset('assets/js/config.js')?>"></script>
    <script src="<?=asset('assets/js/main.js')?>"></script>
    <script src="<?=asset('assets/vendor/js/toastr.min.js')?>"></script>
    <script src="<?=asset('assets/vendor/libs/select2/js/select2.min.js')?>"></script>
    <script src="<?=asset('assets/js/custom-select2.js')?>"></script>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php include 'includes/_aside.php'?>
            <!-- / Menu -->
            <!-- Layout container -->
            <div class="layout-page">
                <?php include 'includes/_nav.php'?>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <?php defineblock('content')?>
                    </div>
                    <?php include 'includes/_footer.php'?>
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
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
    <script>
        <?php if (Flash::has('success')): ?>
            toastr.success('<?=Flash::get('success')?> ')
        <?php endif?>
        <?php if (Flash::has('error')): ?>
            toastr.error('<?=Flash::get('error')?> ')
        <?php endif?>
    </script>
    <?php defineblock('script')?>
</body>

</html>