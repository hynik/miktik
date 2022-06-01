<!DOCTYPE html>
<html lang="en">

<head>
    <?= view("_partials/head") ?>
</head>

<body class="hold-transition <?= (session('logged')) ? 'dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed' : 'register-page' ?>" id="page-top">

    <?php if (session('logged')) : ?>
    
    <div class="wrapper">
        <?= view("_partials/navbar.php"); ?>
        <?= view("_partials/sidebar.php"); ?>

        <div class="content-wrapper">
            <?= view("_partials/content_header.php"); ?>
            <section class="content">
                <div class="container-fluid">
                    <?= $this->renderSection('content') ?>
                </div>
            </section>
        </div>
    </div>
    <?php else : ?>
        <?= $this->renderSection('login_page') ?>
    <?php endif ?>


    <?= view("_partials/scrolltop.php"); ?>
    <?= view("_partials/modal.php"); ?>
    <?= view("_partials/js.php"); ?>
</body>

</html>