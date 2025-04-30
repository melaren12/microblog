<?php

require_once '/var/www/microblog/vendor/autoload.php';

use App\managers\photos\PhotosManager;

$photosManager = PhotosManager::getInstance();
$photosManager->cleanupExpiredArchivedPhotos();