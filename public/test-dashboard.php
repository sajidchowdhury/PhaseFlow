<?php
ob_start();
include __DIR__ . '/../App/View/dashboard/index.php';
$content = ob_get_clean();

include __DIR__ . '/../App/View/layouts/main.php';