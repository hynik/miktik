<!DOCTYPE html>
<html lang="en">

<head>
    <?= view("_partials/head") ?>
</head>

<body class="hold-transition register-page" id="page-top">


    <?= $this->renderSection('login_page') ?>


    <?php if (false) : ?>
    <div class="wrapper">
        <?= view("_partials/navbar.php"); ?>
        <?= view("_partials/sidebar.php"); ?>

        <div class="content-wrapper">
            <?= view("_partials/content_header.php"); ?>
            <section class="container">
                <div class="container-fluid">
                    <?= $this->renderSection('content') ?>
                </div>
            </section>
        </div>
    </div>
    <?php endif ?>


    <?= view("_partials/scrolltop.php"); ?>
    <?= view("_partials/modal.php"); ?>
    <?= view("_partials/js.php"); ?>
</body>

</html>