<?php
ob_start();
include __DIR__ . '/../App/View/pipeline/index.php';
$content = ob_get_clean();

$currentPage = 'pipeline';
include __DIR__ . '/../App/View/layouts/main.php';