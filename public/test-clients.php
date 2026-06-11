<?php
ob_start();
include __DIR__ . '/../App/View/clients/index.php';
$content = ob_get_clean();

$currentPage = 'clients';
include __DIR__ . '/../App/View/layouts/main.php';