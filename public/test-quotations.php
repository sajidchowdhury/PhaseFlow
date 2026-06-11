<?php
ob_start();
include __DIR__ . '/../App/View/quotations/index.php';
$content = ob_get_clean();

$currentPage = 'quotations';
include __DIR__ . '/../App/View/layouts/main.php';