<?php
require_once 'vendor/autoload.php';

if (!isset($_SESSION['user_id'])) {
header('Location: login.php');
exit;
}
header('Location: profile.php');