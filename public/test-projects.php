<?php
ob_start();
include __DIR__ . '/../App/View/projects/index.php';
$content = ob_get_clean();

$currentPage = 'projects';
include __DIR__ . '/../App/View/layouts/main.php';