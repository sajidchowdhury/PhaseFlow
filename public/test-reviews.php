<?php
ob_start();
include __DIR__ . '/../App/View/reviews/index.php';
$content = ob_get_clean();

$currentPage = 'reviews';
include __DIR__ . '/../App/View/layouts/main.php';