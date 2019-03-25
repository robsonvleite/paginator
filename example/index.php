<title><?= $title = "Paginator Happy and @CoffeeCode"; ?></title>
<?php

require __DIR__ . "/../vendor/autoload.php";

$page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
$pager = new \CoffeeCode\Paginator\Paginator();
$pager->pager(100, 10, $page);

?>
<link rel="stylesheet" href="style.css"/>
<link rel="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" href="style.css"/>

<div class="container">
    <h1><?= $title; ?></h1>
    <p>Paginator is simple and is ready to generate results navigation in your application.</p>
    <pre>SELECT * table LIMIT <?= $pager->limit(); ?> OFFSET <?= $pager->offset(); ?>;</pre>

    <?= $pager->render(); ?>
</div>

<p>Se vc usa o Bootstrap no seu projeto use a class "pagination" e ter a saída html do Bootstrap</p>
<?= $pager->render('pagination'); ?>
<?= $pager->render('pagination-sm'); ?>
<?= $pager->render('pagination-lb'); ?>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>

