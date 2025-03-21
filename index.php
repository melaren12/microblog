<?php
require_once 'vendor/autoload.php';
require 'init.php';

if (!isset($_SESSION['user_id'])) {
header('Location: login.php');
exit;
}
header('Location: profile.php');